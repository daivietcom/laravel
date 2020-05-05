/**
 * Created by VnS on 3/31/2016.
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

function socialLoginFail() {
    alert('socialLoginFail');
}

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
        case 'login':
            window.history.back();
            break;
        case 'register':
        case 'reset':
            $('.box-auth').hide();
            $('.box-auth-message').show();
            setTimeout(function(){window.location.href = '/';}, 3000);
            break;
        case 'forgot':
            $('.box-auth').hide();
            $('.box-auth-message').show();
            setTimeout(function(){window.location.href = '/reset-password';}, 3000);
            break;
        default:
            window.location.reload();
    }
}

$(function () {
    $.validator.addMethod(
        'required_without',
        function(value, element, without) {
            return value.length > 0 || $(without).val().length > 0;
        },
        __('Please enter the email address or phone number.')
    );
    $.validator.addMethod(
        'phonenumber',
        function(value, element) {
            return this.optional(element) || /^[0-9]{10,15}$/.test(value);
        },
        __('Not a valid phone number.')
    );
    $.validator.addMethod(
        'regex',
        function(value, element, regexp) {
            return this.optional(element) || (new RegExp(regexp)).test(value);
        },
        __('Please enter a valid format.')
    );
    $.validator.setDefaults({
        messages: {
            email: {
                required: __('This field is required.'),
                email: __('Please enter a valid email address.')
            },
            token: {
                required: __('This field is required.')
            },
            password: {
                required: __('This field is required.'),
                minlength: __('Please enter at least :minlength characters.', {minlength: LEN_PASS})
            },
            password_confirmation: {
                required: __('This field is required.'),
                minlength: __('Please enter at least :minlength characters.', {minlength: LEN_PASS}),
                equalTo: __('Please enter the password again.')
            }
        }
    });
    var validator = $('.box-auth').validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            element.parent().append(error);
        },
        submitHandler: function(form) {
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: $(form).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
    $('.box-auth-social a').on('click', function (event) {
        event.preventDefault();
        var authorize = $(this).attr('href').replace('#','');
        window.open(BASE_URL + '/' + authorize + '/authorize');
    });
});
