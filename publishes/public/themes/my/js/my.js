/**
 * Created by VnS on 3/23/2017.
 */
var __ = function (trans, replaces) {
    if (SCRIPT_LANG[trans] != undefined) {
        trans = SCRIPT_LANG[trans];
    }
    if(replaces != undefined) {
        for (x in replaces) {
            trans = trans.replace(new RegExp(":" + x, "g"), replaces[x]);
        }
    }
    return trans;
};

function loading(hide) {
    var $element = $('.loading');
    if(hide == true) {
        $element.hide();
    } else {
        if($element.length == 0) {
            $element = $('<div class="loading"><div><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span>' + __('Loading, please wait...') + '</span></div>');
            $('body').append($element);
        }
        $element.show();
    }
}

function callbackSuccess(action, data) {
    switch(action) {
        case 'profile':
            $.notify({
                message: __('Profile update successful.')
            },{
                type: 'success'
            });
            break;
        case 'password':
            $.notify({
                message: __('Change password successfully.')
            },{
                type: 'success'
            });
            break;
        default:

    }
}

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var validator = $('.form-horizontal').validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            element.closest('.form-group').append(error);
        },
        submitHandler: function(form) {
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    loading();
                },
                success: function(data, status, xhr) {
                    loading(true);
                    callbackSuccess($(form).attr('name'), data);
                },
                error: function (xhr, status, error) {
                    loading(true);
                    if (xhr.status == 422) {
                        var validatorError = {};
                        $.each(xhr.responseJSON, function(key,messages){
                            validatorError[key] = (typeof messages =='string'?messages:messages[0]);
                        });
                        validator.showErrors(validatorError);
                    }
                }
            });
        }
    });
    $('.btn-password').on('click', function (event) {
        event.preventDefault();
        var $parent = $(this).closest('.form-group'),
            $input = $parent.find('input'),
            $icon = $(this).find('.fa');
        if($input.attr('type') == 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });
    $('#resetApiToken').on('click', function (event) {
        event.preventDefault();
        var $this = $(this),
            $parent = $this.closest('.form-group'),
            $input = $parent.find('input'),
            $icon = $this.find('.fa');
        $.ajax({
            type: 'post',
            url: '/my/reset-api-token',
            dataType: 'json',
            beforeSend: function() {
                $this.prop('disabled', true);
                $icon.addClass('fa-spin');
            },
            success: function(data, status, xhr) {
                if(data.token) {
                    $input.val(data.token);
                    $.notify({
                        message: __('Successfully change.')
                    },{
                        type: 'success'
                    });
                } else {
                    $.notify({
                        message: __('Change failed.')
                    },{
                        type: 'danger'
                    });
                }
                $this.prop('disabled', false);
                $icon.removeClass('fa-spin');
            },
            error: function (xhr, status, error) {
                $this.prop('disabled', false);
                $icon.removeClass('fa-spin');
                $.notify({
                    message: __('Change failed.')
                },{
                    type: 'danger'
                });
            }
        });
    });
});