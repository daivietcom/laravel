/**
 * Created by VnS on 10/4/2016.
 */

var __ = function (trans, replaces) {
    if (SCRIPT_LANG[trans] != undefined) {
        trans = SCRIPT_LANG[trans];
    }
    if (replaces != undefined) {
        for (x in replaces) {
            trans = trans.replace(new RegExp(":" + x, "g"), replaces[x]);
        }
    }
    return trans;
};

var callback = function (name, data) {
    var scope = angular.element(document.body).scope();
    scope[name](data);
};

var VnSapp = angular.module('VnSapp', [
    'pascalprecht.translate',
    'ui.router',
    'ngResource',
    'ngMessages',
    'ngAnimate',
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'angular-loading-bar',
    'ngTable',
    'ncy-angular-breadcrumb',
    'ngTagsInput',
    'ui.tree',
    'ui.tinymce',
    'ui-notification',
    'angular-clipboard',
    'as.sortable'
]);

VnSapp.run(function ($rootScope, $state, $stateParams, $http, $timeout, $window, $document, $uibModal, $location, Dialog, clipboard, Notification) {
    console.log(__('Copyright console'), 'background: #222; color: #bada55; font-size: 30px', 'background: #222; color: #da251d; font-size: 30px');
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;

    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
        $rootScope.siteTitle = null;
        $rootScope.siteDescription = null;
        $rootScope.siteImage = null;
        $rootScope.siteIcon = null;
        $rootScope.smallTitle = null;
        $rootScope.ncyBreadcrumb = null;
        $rootScope.tgState = false;
    });

    $rootScope.tgStateEvent = function () {
        $rootScope.tgState = !$rootScope.tgState;
    };

    $rootScope.logout = function (goTo) {
        Dialog.confirm('Are you sure you want to logout?').result.then(function () {

        });
    };

    $rootScope.goTop = function () {
        $document.scrollTopAnimated(0);
    };

    $rootScope.getWindowDimensions = function () {
        $rootScope.windowHeight = $window.innerHeight;
        $rootScope.windowWidth = $window.innerWidth;
        return {
            'h': $window.innerHeight,
            'w': $window.innerWidth
        };
    };
    $rootScope.getWindowDimensions();
    angular.element($window).bind('resize', function () {
        $rootScope.getWindowDimensions();
    });

    $rootScope.tinymceOptions = {
        language: CODE_LANG,
        language_url: JS_PATH + 'tinymce/langs/' + CODE_LANG + '.js',
        skin_url: JS_PATH + 'tinymce/skins/vns',
        trusted: true,
        height: 300,
        plugins: [
            'link image lists preview hr pagebreak',
            'wordcount code fullscreen nonbreaking',
            'table textcolor paste textcolor colorpicker textpattern'
        ],
        external_plugins: {
            'vnsmedia': JS_PATH + 'tinymce/plugins/vnsmedia/plugin.min.js'
        },
        menubar: false,
        toolbar: [
            'code vnsmedia image | bold italic strikethrough | bullist numlist | blockquote | alignleft aligncenter alignright | link unlink | pagebreak more',
            'formatselect | underline | alignjustify | forecolor pastetext removeformat | outdent indent | undo redo | fullscreen'
        ]
    };

    $rootScope.getThumb = function (src) {
        return src.replace(/([0-9]{4})\/([0-9]{2})\/([0-9a-z]{32})\.(jpg|png)/, '$1/$2/thumbs/$3.$4');
    };


    $rootScope.viewPost = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'iframe.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.url = $location.protocol() + "://" + $location.host() + '/' + row.slug + '-' + row.id;
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
                $scope.reload = function () {
                    window.frames['viewPage'].location.reload();
                };
            },
            backdrop: 'static',
            windowClass: 'modal-iframe'
        });
    };

    $rootScope.postUrl = function (row) {
        clipboard.copyText($location.protocol() + "://" + $location.host() + '/' + row.slug + '-' + row.id);
        Notification.success(__('Copied'))
    };

});

VnSapp.constant('mediaType', function (type) {
    switch (type) {
        case 'image/jpeg':
        case 'image/bmp':
        case 'image/x-ms-bmp':
        case 'image/png':
        case 'image/x-icon':
            return 'image';
        case 'audio/ogg':
        case 'audio/x-ms-wma':
        case 'audio/mp3':
        case 'audio/x-aac':
        case 'audio/x-wav':
            return 'audio';
        case 'video/h264':
        case 'video/mp4':
        case 'video/mpeg':
        case 'video/x-flv':
        case 'video/x-msvideo':
            return 'video';
        case 'application/vnd.ms-excel':
        case 'application/msword':
        case 'application/vnd.ms-htmlhelp':
        case 'application/pdf':
        case 'text/plain':
            return 'document';
        case 'application/x-msdownload':
        case 'application/vnd.ms-office':
            return 'application';
        case 'application/octet-stream':
            return 'binary';
        default:
            return 'other';
    }
});

VnSapp.constant('Thumbs', {
    back: IMAGE_PATH + 'vns-media/back.png',
    folderEmpty: IMAGE_PATH + 'vns-media/folderEmpty.png',
    folder: IMAGE_PATH + 'vns-media/folder.png',
    audio: IMAGE_PATH + 'vns-media/audio.png',
    video: IMAGE_PATH + 'vns-media/video.png',
    document: IMAGE_PATH + 'vns-media/document.png',
    application: IMAGE_PATH + 'vns-media/application.png',
    binary: IMAGE_PATH + 'vns-media/binary.png',
    other: IMAGE_PATH + 'vns-media/other.png'
});

VnSapp.filter('parseDate', function () {
    return function (input) {
        return new Date(input);
    };
});

VnSapp.filter('parseAuthor', function () {
    return function (input) {
        return input.map(function (o) {
            return o.name;
        }).join(', ');
    };
});

VnSapp.filter('n2Br', function () {
    return function (str) {
        return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
    };
});

VnSapp.filter('fileSize', function () {
    return function (size) {
        var i = -1;
        var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
        do {
            size = size / 1024;
            i++;
        } while (size > 1024);

        return Math.max(size, 0.1).toFixed(1) + byteUnits[i];
    }
});

VnSapp.filter('capitalize', function () {
    return function (input, all) {
        var word = (all) ? input.split(' ') : [input];
        for (var i = 0; i < word.length; i++) {
            var j = word[i].charAt(0).toUpperCase();
            word[i] = j + word[i].substr(1);
        }
        return word.join(' ');
    }
});

VnSapp.factory('httpApiInterceptor', function () {
    return {
        request: function (config) {
            var patt = new RegExp(API_URL);
            if (patt.test(config.url)) {
                config.timeout = 600000;
                if (config.params == undefined) {
                    config.params = {api_token: API_TOKEN};
                } else {
                    config.params.api_token = API_TOKEN;
                }
            }
            return config;
        }
    }
});

VnSapp.factory('httpRequestInterceptor', function () {
    return {
        request: function (config) {
            var patt = new RegExp(API_URL);
            if (patt.test(config.url)) {
                config.params.api_token = API_TOKEN;
            }
            return config;
        }
    };
});

VnSapp.factory('Dialog', function ($uibModal) {
    return {
        notify: function (msg, header, size) {
            header = (angular.isDefined(header)) ? header : __('Notify');
            size = (angular.isDefined(size)) ? size : 'sm';
            return $uibModal.open({
                animation: true,
                templateUrl: 'dialog/confirm.html',
                controller: function ($scope, $uibModalInstance, data) {
                    $scope.msg = data.msg;
                    $scope.header = data.header;
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                },
                backdrop: 'static',
                size: size,
                resolve: {
                    data: function () {
                        return {
                            header: header,
                            msg: msg
                        };
                    }
                }
            });
        },
        confirm: function (msg, header, size) {
            header = (angular.isDefined(header)) ? header : __('Confirmation');
            size = (angular.isDefined(size)) ? size : 'sm';
            return $uibModal.open({
                animation: true,
                templateUrl: 'dialog/confirm.html',
                controller: function ($scope, $uibModalInstance, data) {
                    $scope.msg = data.msg;
                    $scope.header = data.header;
                    $scope.no = function () {
                        $uibModalInstance.dismiss('no');
                    };

                    $scope.yes = function () {
                        $uibModalInstance.close('yes');
                    };
                },
                backdrop: 'static',
                size: size,
                resolve: {
                    data: function () {
                        return {
                            header: header,
                            msg: msg
                        };
                    }
                }
            });
        },
        error: function (msg, header, size) {
            header = (angular.isDefined(header)) ? header : __('Error');
            size = (angular.isDefined(size)) ? size : 'sm';
            return $uibModal.open({
                animation: true,
                templateUrl: 'dialog/error.html',
                controller: function ($scope, $uibModalInstance, data) {
                    $scope.msg = data.msg;
                    $scope.header = data.header;
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                },
                backdrop: 'static',
                size: size,
                resolve: {
                    data: function () {
                        return {
                            header: header,
                            msg: msg
                        };
                    }
                }
            });
        },
        wait: function (msg, header, progress, size) {
            header = (angular.isDefined(header)) ? header : __('Please wait');
            size = (angular.isDefined(size)) ? size : 'sm';
            progress = (angular.isDefined(progress)) ? progress : 100;
            return $uibModal.open({
                animation: true,
                templateUrl: 'dialog/wait.html',
                controller: function ($scope, $uibModalInstance, $timeout, data) {
                    $scope.msg = data.msg;
                    $scope.header = data.header;
                    $scope.progress = data.progress;
                    $scope.$on('dialogs.wait.complete', function () {
                        $timeout(function () {
                            $uibModalInstance.close();
                            $scope.$destroy();
                        });
                    });

                    $scope.$on('dialogs.wait.message', function (evt, args) {
                        $scope.msg = (angular.isDefined(args.msg)) ? args.msg : $scope.msg;
                    });

                    $scope.$on('dialogs.wait.progress', function (evt, args) {
                        $scope.msg = (angular.isDefined(args.msg)) ? args.msg : $scope.msg;
                        $scope.progress = (angular.isDefined(args.progress)) ? args.progress : $scope.progress;
                    });

                    $scope.getProgress = function () {
                        return {'width': $scope.progress + '%'};
                    };
                },
                backdrop: 'static',
                size: size,
                resolve: {
                    data: function () {
                        return {
                            header: header,
                            msg: msg,
                            progress: progress
                        };
                    }
                }
            });
        }
    }
});

VnSapp.factory('vnsMedia', function ($http, $uibModal) {
    return {
        get: function (folder, type) {
            if (angular.isDefined(type)) {
                return $http.get(API_URL + '/media/' + folder, {
                    params: {
                        'type[]': type
                    }
                });
            } else {
                return $http.get(API_URL + '/media/' + folder);
            }
        },
        rename: function (media) {
            return $http.post(API_URL + '/media/rename', {
                id: media.id,
                name: media.name,
                type: media.type
            });
        },
        dialog: function (config) {
            return $uibModal.open({
                animation: true,
                templateUrl: 'vnsMedia/modal.html',
                controller: 'vnsMediaModalCtrl',
                backdrop: 'static',
                windowClass: 'modal-media',
                size: 'lg',
                resolve: {
                    config: config
                }
            });
        }
    };
});

VnSapp.directive('ngConfim', function () {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, ctrl) {
            if (!ctrl) return;
            var otherInput = elem.inheritedData('$formController')[attrs.ngConfim];

            ctrl.$parsers.push(function (value) {
                if (value === otherInput.$viewValue) {
                    ctrl.$setValidity('confim', true);
                    return value;
                }
                ctrl.$setValidity('confim', false);
            });

            otherInput.$parsers.push(function (value) {
                ctrl.$setValidity('confim', value === ctrl.$viewValue);
                return value;
            });
        }
    }
});

VnSapp.directive('inputCopy', function () {
    return {
        restrict: 'A',
        scope: {
            fromValue: '=inputCopy',
            toValue: '=ngModel'
        },
        controller: function ($scope) {
            var scopeWatch = $scope.$watch(function (scope) {
                return scope.fromValue
            }, function (newValue, oldValue) {
                if ($scope.toValue == oldValue) {
                    $scope.toValue = newValue;
                }
            }, true);
            $scope.$on('$destroy', function () {
                scopeWatch();
            });
        }
    };
});

VnSapp.directive('inputSlug', function () {
    return {
        restrict: 'A',
        scope: {
            fromValue: '=inputSlug',
            toValue: '=ngModel'
        },
        controller: function ($scope) {
            var toUri = function (str, replacement) {
                if (!angular.isDefined(str)) {
                    return '';
                }
                if (!angular.isDefined(replacement)) {
                    replacement = '-';
                }
                var unicode = {
                    'a': 'áàảãạăắặằẳẵâấầẩẫậ',
                    "c": "ç",
                    'd': 'đ',
                    'e': 'éèẻẽẹêếềểễệ',
                    'i': 'íìỉĩị',
                    'o': 'óòỏõọôốồổỗộơớờởỡợ',
                    'u': 'úùủũụưứừửữự',
                    'y': 'ýỳỷỹỵ',
                };
                str = str.replace(/^\s+|\s+$/g, '')
                    .toLowerCase()
                ;

                for (nonUnicode in unicode) {
                    for (var i = 0, l = unicode[nonUnicode].length; i < l; i++) {
                        str = str.replace(new RegExp(unicode[nonUnicode].charAt(i), 'g'), nonUnicode);
                    }
                }
                str = str.replace(/[^0-9a-zA-Z]/g, replacement)
                    .replace(new RegExp(replacement + '{2,}', 'g'), replacement)
                    .replace(new RegExp('(^' + replacement + ')|(' + replacement + '$)', 'g'), '')
                ;
                return str;
            };

            var scopeWatch = $scope.$watch(function (scope) {
                return scope.fromValue
            }, function (newValue, oldValue) {
                if ($scope.toValue == toUri(oldValue)) {
                    $scope.toValue = toUri(newValue);
                }
            }, true);
            $scope.$on('$destroy', function () {
                scopeWatch();
            });
        }
    };
});

VnSapp.directive('inputVnsCoin', function () {
    return {
        restrict: 'A',
        scope: {
            inputVnsCoin: '='
        },
        replace: true,
        template: '<input class="form-control vns-coin" type="text" uib-tooltip="{{val}}" tooltip-trigger="focus" tooltip-placement="top" />',
        controller: function ($scope, $element) {
            var rate = 1000,
                formatNumber = function (num) {
                    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                };
            if (angular.isUndefined($scope.inputVnsCoin)) {
                $scope.inputVnsCoin = 0;
            }
            $scope.val = formatNumber($scope.inputVnsCoin * rate) + ' VNĐ';
            $element.val(formatNumber($scope.inputVnsCoin));
            $element.bind('keyup', function () {
                var val = $element.val();
                $scope.inputVnsCoin = parseInt(val.replace(/\D/g, ''));
                $element.val(formatNumber($scope.inputVnsCoin));
                $scope.val = formatNumber($scope.inputVnsCoin * rate) + ' VNĐ';
            });
        }
    };
});

VnSapp.directive('inputAmount', function () {
    return {
        restrict: 'A',
        scope: {
            inputAmount: '='
        },
        replace: true,
        template: '<input class="form-control" type="text" />',
        controller: function ($scope, $element) {
            var formatNumber = function (num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            };
            if (angular.isUndefined($scope.inputAmount)) {
                $scope.inputAmount = 0;
            }
            $element.val(formatNumber($scope.inputAmount));
            $element.bind('keyup', function () {
                var val = $element.val();
                if (val == '') {
                    $scope.inputAmount = 0;
                    $element.val('');
                } else {
                    $scope.inputAmount = parseInt(val.replace(/\D/g, ''));
                    $element.val(formatNumber($scope.inputAmount));
                }
            });
        }
    };
});

VnSapp.directive('requiredAny', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function postLink(scope, elem, attrs, ctrl) {

            if (!ctrl || !attrs.requiredAny) {
                return;
            }

            if (!scope.__requiredAnyGroups) {
                scope.__requiredAnyGroups = {}
            }
            var groups = scope.__requiredAnyGroups;

            if (!groups[attrs.requiredAny]) {
                groups[attrs.requiredAny] = {};
            }
            var group = groups[attrs.requiredAny];

            // Create the entry for this control
            group[attrs.ngModel] = {
                ctrl: ctrl,
                hasValue: false
            };

            ctrl.$validators.requiredAny = function (view, value) {
                var thisCtrl = group[attrs.ngModel],
                    ctrlValue = (typeof value !== 'undefined') && value,
                    oneHasValue = false;

                thisCtrl.hasValue = ctrlValue;

                for (var prop in group) {
                    if (group.hasOwnProperty(prop) && group[prop].hasValue) {
                        oneHasValue = true;
                        break;
                    }
                }

                for (var prop in group) {
                    if (group.hasOwnProperty(prop) && thisCtrl != group[prop]) {
                        group[prop].ctrl.$setValidity('requiredAny', oneHasValue);
                    }
                }

                return oneHasValue;
            };
        }
    };
});

VnSapp.directive('inputPassword', function () {
    return {
        restrict: 'AE',
        scope: {
            inputValue: '=ngModel'
        },
        link: function (scope, elem, attrs) {
            elem.addClass('input-password');
            var btn = elem.find('i'),
                input = elem.find('input');
            btn.bind('click', function () {
                if (btn.hasClass('fa-eye')) {
                    btn.removeClass('fa-eye').addClass('fa-eye-slash');
                    input.attr('type', 'password');
                }
                else {
                    btn.removeClass('fa-eye-slash').addClass('fa-eye');
                    input.attr('type', 'text');
                }
            });
        },
        template: '<input class="form-control" type="password" ng-model="inputValue"><i class="fa fa-eye-slash"></i>'
    };
});

VnSapp.directive('clock', function (dateFilter, $timeout) {
    return {
        restrict: 'E',
        scope: {
            format: '@'
        },
        link: function (scope, element, attrs) {
            var updateTime = function () {
                var now = new Date();

                element.html(dateFilter(now, scope.format));
                $timeout(updateTime, 1000);
            };

            updateTime();
        }
    };
});

VnSapp.directive('eventTime', function () {
    return {
        restrict: 'E',
        replace: false,
        scope: {
            startTime: '=',
            stopTime: '='
        },
        controller: function ($scope, $element, $timeout, $compile) {
            var start = new Date($scope.startTime),
                stop = new Date($scope.stopTime),
                updateTime = function () {
                    var now = new Date(),
                        diff;
                    if (now < start) {
                        diff = Math.floor((start.getTime() - now.getTime()) / 1000);
                        $scope.status = -1;
                    } else if (now > stop) {
                        $scope.days = 0;
                        $scope.hours = 0;
                        $scope.minutes = 0;
                        $scope.seconds = 0;
                        $scope.status = 0;
                        return;
                    } else {
                        diff = Math.floor((stop.getTime() - now.getTime()) / 1000);
                        $scope.status = 1;
                    }
                    $scope.days = Math.floor(diff / 86400);
                    diff -= $scope.days * 86400;
                    $scope.hours = Math.floor(diff / 3600) % 24;
                    diff -= $scope.hours * 3600;
                    $scope.minutes = Math.floor(diff / 60) % 60;
                    diff -= $scope.minutes * 60;
                    $scope.seconds = diff % 60;

                    $timeout(updateTime, 1000);
                };
            updateTime();
            $element.append($compile($element.contents())($scope));
        }
    };
});

VnSapp.directive('dropdownMultiselect', function () {
    return {
        restrict: 'E',
        scope: {
            model: '=ngModel',
            disabled: '=ngDisabled',
            emptyLabel: '@emptyLabel',
            options: '='
        },
        templateUrl: 'dropdown-select/multi.html',

        controller: function ($scope) {

            $scope.label = $scope.emptyLabel;

            $scope.openDropdown = function () {

                $scope.open = !$scope.open;

            };

            $scope.$watchCollection('options', function (newVal, oldVal) {
                if ($scope.model.length == 0 || newVal.length == 0) {
                    $scope.label = $scope.emptyLabel;
                } else {
                    $scope.label = $scope.options.reduce(function (obj, value) {
                        if ($scope.model.indexOf(value.id) !== -1) {
                            obj.push(value.name);
                        }
                        return obj;
                    }, []).join(', ');
                }
            });

            $scope.$watchCollection('model', function (newVal, oldVal) {
                if (newVal.length == 0 || $scope.options.length == 0) {
                    $scope.label = $scope.emptyLabel;
                } else {
                    $scope.label = $scope.options.reduce(function (obj, value) {
                        if ($scope.model.indexOf(value.id) !== -1) {
                            obj.push(value.name);
                        }
                        return obj;
                    }, []).join(', ');
                }
            });

            $scope.selectAll = function () {

                $scope.model = [];

                for (var index in $scope.options) {
                    $scope.model.push($scope.options[index].id);
                }

            };

            $scope.deselectAll = function () {

                $scope.model = [];

            };

            $scope.toggleSelectItem = function (option) {

                var intIndex = -1;
                for (var index in $scope.model) {
                    if ($scope.model[index] == option.id) {
                        intIndex = index;
                        break;
                    }
                }

                if (intIndex >= 0) {
                    $scope.model.splice(intIndex, 1);
                }
                else {
                    $scope.model.push(option.id);
                }

            };

            $scope.getClassIcon = function (option) {

                var varClassName = 'fa fa-square-o';

                for (var index in $scope.model) {
                    if ($scope.model[index] == option.id) {
                        varClassName = 'fa fa-check-square-o';
                        break;
                    }
                }

                return (varClassName);

            };

            $scope.getClassSpace = function (option) {
                if (typeof option.space !== 'undefined') {
                    return 'space-span-' + option.space;
                } else {
                    return '';
                }
            };
        }
    }

});

VnSapp.directive('dropdownSelect', function () {
    return {
        restrict: 'E',
        scope: {
            model: '=ngModel',
            disabled: '=ngDisabled',
            emptyLabel: '@emptyLabel',
            options: '='
        },
        templateUrl: 'dropdown-select/single.html',

        controller: function ($scope) {

            $scope.label = $scope.emptyLabel;

            $scope.openDropdown = function () {

                $scope.open = !$scope.open;

            };

            $scope.$watchCollection('options', function (newVal, oldVal) {
                if (newVal != undefined) {
                    for (var i = 0, len = newVal.length; i < len; i++) {
                        if (newVal[i].id != undefined && newVal[i].id === $scope.model) {
                            $scope.label = newVal[i].name;
                            break;
                        }
                    }
                }
            });

            $scope.toggleSelectItem = function (option) {

                if ($scope.model == option.id) {
                    $scope.model = undefined;
                    $scope.label = $scope.emptyLabel;
                } else {
                    $scope.model = option.id;
                    $scope.label = option.name;
                    $scope.open = !$scope.open;
                }

            };

            $scope.getClassIcon = function (option) {

                var varClassName = 'fa fa-circle-o';

                if ($scope.model == option.id) {

                    varClassName = 'fa fa-dot-circle-o';

                }

                return (varClassName);

            };

            $scope.getClassSpace = function (option) {
                if (typeof option.space !== 'undefined') {
                    return 'space-span-' + option.space;
                } else {
                    return '';
                }
            };
        }
    }

});

VnSapp.directive('checklistModel', function ($parse, $compile) {
    // contains
    function contains(arr, item, comparator) {
        if (angular.isArray(arr)) {
            for (var i = arr.length; i--;) {
                if (comparator(arr[i], item)) {
                    return true;
                }
            }
        }
        return false;
    }

    // add
    function add(arr, item, comparator) {
        arr = angular.isArray(arr) ? arr : [];
        if (!contains(arr, item, comparator)) {
            arr.push(item);
        }
        return arr;
    }

    // remove
    function remove(arr, item, comparator) {
        if (angular.isArray(arr)) {
            for (var i = arr.length; i--;) {
                if (comparator(arr[i], item)) {
                    arr.splice(i, 1);
                    break;
                }
            }
        }
        return arr;
    }

    // http://stackoverflow.com/a/19228302/1458162
    function postLinkFn(scope, elem, attrs) {
        // exclude recursion, but still keep the model
        var checklistModel = attrs.checklistModel;
        attrs.$set("checklistModel", null);
        // compile with `ng-model` pointing to `checked`
        $compile(elem)(scope);
        attrs.$set("checklistModel", checklistModel);

        // getter for original model
        var checklistModelGetter = $parse(checklistModel);
        var checklistChange = $parse(attrs.checklistChange);
        var checklistBeforeChange = $parse(attrs.checklistBeforeChange);
        var ngModelGetter = $parse(attrs.ngModel);


        var comparator = angular.equals;

        if (attrs.hasOwnProperty('checklistComparator')) {
            if (attrs.checklistComparator[0] == '.') {
                var comparatorExpression = attrs.checklistComparator.substring(1);
                comparator = function (a, b) {
                    return a[comparatorExpression] === b[comparatorExpression];
                };

            } else {
                comparator = $parse(attrs.checklistComparator)(scope.$parent);
            }
        }

        // watch UI checked change
        var unbindModel = scope.$watch(attrs.ngModel, function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }

            if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
                ngModelGetter.assign(scope, contains(checklistModelGetter(scope.$parent), getChecklistValue(), comparator));
                return;
            }

            setValueInChecklistModel(getChecklistValue(), newValue);

            if (checklistChange) {
                checklistChange(scope);
            }
        });

        // watches for value change of checklistValue
        var unbindCheckListValue = scope.$watch(getChecklistValue, function (newValue, oldValue) {
            if (newValue != oldValue && angular.isDefined(oldValue) && scope[attrs.ngModel] === true) {
                var current = checklistModelGetter(scope.$parent);
                checklistModelGetter.assign(scope.$parent, remove(current, oldValue, comparator));
                checklistModelGetter.assign(scope.$parent, add(current, newValue, comparator));
            }
        }, true);

        var unbindDestroy = scope.$on('$destroy', destroy);

        function destroy() {
            unbindModel();
            unbindCheckListValue();
            unbindDestroy();
        }

        function getChecklistValue() {
            return attrs.checklistValue ? $parse(attrs.checklistValue)(scope.$parent) : attrs.value;
        }

        function setValueInChecklistModel(value, checked) {
            var current = checklistModelGetter(scope.$parent);
            if (angular.isFunction(checklistModelGetter.assign)) {
                if (checked === true) {
                    checklistModelGetter.assign(scope.$parent, add(current, value, comparator));
                } else {
                    checklistModelGetter.assign(scope.$parent, remove(current, value, comparator));
                }
            }

        }

        // declare one function to be used for both $watch functions
        function setChecked(newArr, oldArr) {
            if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
                setValueInChecklistModel(getChecklistValue(), ngModelGetter(scope));
                return;
            }
            ngModelGetter.assign(scope, contains(newArr, getChecklistValue(), comparator));
        }

        // watch original model change
        // use the faster $watchCollection method if it's available
        if (angular.isFunction(scope.$parent.$watchCollection)) {
            scope.$parent.$watchCollection(checklistModel, setChecked);
        } else {
            scope.$parent.$watch(checklistModel, setChecked, true);
        }
    }

    return {
        restrict: 'A',
        priority: 1000,
        terminal: true,
        scope: true,
        compile: function (tElement, tAttrs) {

            if (!tAttrs.checklistValue && !tAttrs.value) {
                throw 'You should provide `value` or `checklist-value`.';
            }

            // by default ngModel is 'checked', so we set it if not specified
            if (!tAttrs.ngModel) {
                // local scope var storing individual checkbox model
                tAttrs.$set("ngModel", "checked");
            }

            return postLinkFn;
        }
    };
});

VnSapp.directive('sidenav', function () {
    return {
        restrict: 'C',
        controller: function ($scope) {
            $scope.last = LAST_LOGIN;
            $scope.getPlatform = function (platform) {
                var icon = '';
                if (/Windows/.test(platform)) {
                    icon = 'fa fa-windows';
                } else if (/Android/.test(platform)) {
                    icon = 'fa fa-android';
                } else if (/(Mac|iOS)/.test(platform)) {
                    icon = 'fa fa-apple';
                } else if (/Linux/.test(platform)) {
                    icon = 'fa fa-linux';
                }
                return icon;
            }
        },
        link: function (scope, elem, attrs) {
            var dropdown = elem.children('.navbar-cpanel').children('.dropdown');
            dropdown.bind('click', function ($event) {
                var $this = angular.element(this);
                if ($this.hasClass('open')) {
                    $this.removeClass('open');
                } else {
                    $this.addClass('open');
                }
            });
        }
    };
});

VnSapp.directive('vnsTable', function () {
    return {
        restrict: 'A',
        replace: true,
        scope: {
            params: '=vnsTable'
        },
        controller: 'vnsTableCtrl',
        templateUrl: 'vnsTable.html'
    };
});

VnSapp.factory('vnsTableParams', function () {
    var self = this;
    return function (baseParams) {
        self.params = angular.merge({
            count: 10,
            counts: [10, 25, 50, 100],
            page: 1,
            filter: {},
            sorting: {}
        }, baseParams);

        return {
            rows: [],
            total: 0,
            getFirstAction: function () {
                return self.params.actions.shift();
            },
            getActions: function () {
                return self.params.actions;
            },
            getColumns: function () {
                return self.params.columns;
            },
            getFilters: function () {
                var filters = {};

                for (field in self.params.columns) {
                    if (self.params.columns[field].filter != undefined) {
                        filters[field] = {
                            value: null
                        };
                        if (typeof self.params.columns[field].filter == 'string') {
                            filters[field].type = self.params.columns[field].filter;
                        } else {
                            filters[field].type = 'select';
                            filters[field].data = self.params.columns[field].filter;
                        }
                        delete self.params.columns[field].filter;
                    }
                }

                return Object.keys(filters).length > 0 ? filters : undefined;
            },
            getTotal: function () {
                return this.total;
            },
            setTotal: function (total) {
                this.total = total;
            },
            setFilter: function (filters) {
                self.params.filter = filters;
            },
            setSorting: function (sorting) {
                self.params.sorting = sorting;
            },
            reload: function () {
                this.rows = self.params.getData(this);
            },
            url: function () {
                var url = {
                    page: self.params.page,
                    count: self.params.count
                };
                if(Object.keys(self.params.filter).length > 0) {
                    for (key in self.params.filter) {
                        url['filter['+key+']'] = self.params.filter[key];
                    }
                }
                if(Object.keys(self.params.sorting).length > 0) {
                    for (key in self.params.sorting) {
                        url['sorting['+key+']'] = self.params.sorting[key];
                    }
                }
                return url;
            }
        };
    };
});

VnSapp.controller('vnsTableCtrl', function ($rootScope, $scope) {
    $scope.filter = function () {
        var filter = {};
        for (field in $scope.$filters) {
            if ($scope.$filters[field].value !== null && $scope.$filters[field].value !== '') {
                filter[field] = $scope.$filters[field].value;
            }
        }
        $scope.params.setFilter(filter);
        $scope.params.reload();
    };

    $scope.sortInit = function () {
        var sorting = {};
        for (field in $scope.$columns) {
            if(['asc','desc'].indexOf($scope.$columns[field].sortable) !== -1) {
                sorting[field] = $scope.$columns[field].sortable;
            }
        }
        $scope.params.setSorting(sorting);
    };

    $scope.sort = function (field) {
        if ($scope.$columns[field].sortable != undefined) {
            switch ($scope.$columns[field].sortable) {
                case 'asc':
                    $scope.$columns[field].sortable = 'desc';
                    break;
                case 'desc':
                    $scope.$columns[field].sortable = true;
                    break;
                default:
                    $scope.$columns[field].sortable = 'asc';
            }
            $scope.sortInit();
            $scope.params.reload();
        }
    };

    $scope.getValue = function (field, row, option) {
        if (option.value != undefined) {
            var value = angular.copy(option.value);
            for (key in row) {
                value = value.replace(new RegExp(':'+key), row[key]);
            }
            return value;
        }
        return option.format ?option.format(row[field]):row[field];
    };

    $scope.$firstAction = $scope.params.getFirstAction();
    $scope.$actions = $scope.params.getActions();
    $scope.$columns = $scope.params.getColumns();
    $scope.$total = $scope.params.getTotal();
    $scope.$filters = $scope.params.getFilters();
    $scope.sortInit();
    $scope.params.reload();

    $scope.$watch('params.rows', function (newValue, oldValue) {
        $scope.$rows = newValue;
    });
});

VnSapp.directive('dropzone', function () {
    return {
        restrict: 'C',
        scope: {
            currentFolder: '@',
            onSuccess: '&',
            callback: '=',
            processing: '='
        },
        link: function (scope, element, attrs) {

            var config = {
                url: API_URL + '/media/upload?api_token=' + API_TOKEN,
                maxFilesize: 100,
                paramName: 'file',
                parallelUploads: 1,
                autoProcessQueue: true,
                init: function () {
                    this.on('addedfile', function (file) {
                        scope.processing = true;
                    });
                    this.on('sending', function (file, xhr, data) {
                        data.append('folder', scope.currentFolder);
                        data.append('callback', scope.callback);
                    });
                    this.on('success', function (file, response) {
                        scope.onSuccess({media: response});
                    });
                    this.on('queuecomplete', function () {
                        scope.processing = false;
                    });
                }
            };

            var dropzone = new Dropzone(element[0], config);
        }
    }
});

VnSapp.directive('vnsMedia', function () {
    return {
        restrict: 'AE',
        scope: {
            config: '=mediaConfig',
            medias: '=ngModel'
        },
        controller: 'vnsMediaCtrl',
        templateUrl: 'vnsMedia.html'
    };
});

VnSapp.directive('vnsMediaContextMenu', function ($rootScope, $uibModal, $http, $filter, Dialog, Notification) {
    var renderContextMenu = function ($scope, event) {
        if (!$) {
            var $ = angular.element;
        }
        $(event.currentTarget).addClass('context');
        var $contextMenu = $('<div>');
        $contextMenu.addClass('vns-media-context-menu clearfix');
        var $ul = $('<ul>');
        $ul.addClass('dropdown-menu');
        $ul.attr({'role': 'menu'});

        var items = [];


        if ($scope.media.type == 'image') {
            items.push({
                type: 'action',
                icon: 'fa fa-eye',
                text: __('View'),
                callback: function ($itemScope) {
                    var modalView = $uibModal.open({
                        animation: true,
                        templateUrl: 'vnsMedia/modal/view.html',
                        controller: 'vnsMediaViewCtrl',
                        windowClass: 'modal-media-view',
                        backdrop: 'static',
                        resolve: {
                            data: function () {
                                return angular.copy($itemScope);
                            }
                        }
                    });
                }
            });
        }

        if ($scope.media.type != 'folder') {
            items.push({
                type: 'action',
                icon: 'fa fa-link',
                text: __('Show URL'),
                callback: function ($itemScope) {
                    $uibModal.open({
                        animation: true,
                        templateUrl: 'vnsMedia/modal/url.html',
                        controller: 'vnsMediaShowURLCtrl',
                        backdrop: 'static',
                        resolve: {
                            data: function () {
                                return angular.copy($itemScope);
                            }
                        }
                    });
                }
            });
        }

        if ($scope.defaultConfig.rename == true) {
            items.push({
                type: 'action',
                icon: 'fa fa-i-cursor',
                text: __('Rename'),
                callback: function ($itemScope) {
                    $uibModal.open({
                        animation: true,
                        templateUrl: 'vnsMedia/modal/rename.html',
                        controller: 'vnsMediaRenameCtrl',
                        backdrop: 'static',
                        resolve: {
                            data: $itemScope
                        }
                    });
                }
            });
        }
        items.push({
            type: 'action',
            icon: 'fa fa-scissors',
            text: __('Move'),
            callback: function ($itemScope) {

            }
        });
        if ($scope.defaultConfig.delete == true) {
            items.push({
                type: 'action',
                icon: 'fa fa-times',
                text: __('Delete'),
                class: 'text-danger',
                callback: function ($itemScope) {
                    var modal = Dialog.confirm(__('Are you sure you want to delete <b>:name</b>?', {name: $itemScope.name}));
                    modal.result.then(function () {
                        $http.post(API_URL + '/media/delete/' + $itemScope.id, {
                            type: $itemScope.type,
                            callback: $scope.defaultConfig.callback
                        }).then(function () {
                            var vnsMediaScope = angular.element(document.querySelector('.vns-media-container')).scope();
                            if ($itemScope.type == 'folder') {
                                vnsMediaScope.folders -= 1;
                            } else {
                                vnsMediaScope.files -= 1;
                            }
                            for (var i in vnsMediaScope.medias) {
                                if (vnsMediaScope.medias[i].id == $itemScope.id) {
                                    vnsMediaScope.medias.splice(i, 1);
                                    break;
                                }
                            }
                            Notification.success('Delete successfully');
                        }, function () {
                            Notification.error('Delete failed');
                        });
                    });
                }
            });
        }
        items.push(null);
        items.push({
            type: 'header',
            text: __($scope.media.type == 'folder' ? 'Folder info' : 'File info')
        });
        items.push({
            icon: 'fa fa-tag',
            text: $scope.media.name
        });
        if ($scope.media.type != 'folder') {
            if (parseInt($scope.media.width) > 0) {
                items.push({
                    icon: 'fa fa-arrows',
                    text: $scope.media.width + 'x' + $scope.media.height
                });
            }
            items.push({
                icon: 'fa fa-pie-chart',
                text: $filter('fileSize')($scope.media.size)
            });
        }
        else {
            items.push({
                icon: 'fa fa-pie-chart',
                text: __(':files Files - :folders Folders', {
                    files: $scope.media.files,
                    folders: $scope.media.folders
                })
            });
        }

        items.push({
            icon: 'fa fa-calendar',
            text: $scope.media.created_at
        });

        angular.forEach(items, function (item, i) {
            var $li = $('<li>');
            if (item === null) {
                $li.addClass('divider');
            } else {
                switch (item.type) {
                    case 'action':
                        var $a = $('<a>');
                        $a.attr({tabindex: '-1', href: '#'});
                        if (angular.isDefined(item.class)) {
                            var $span = $('<span>');
                            $span.addClass(items[i].class);
                            $span.html((angular.isDefined(item.icon) ? '<i class="' + item.icon + '"></i> ' : '') + item.text);
                            $a.append($span);
                        } else {
                            $a.html((angular.isDefined(item.icon) ? '<i class="' + item.icon + '"></i> ' : '') + item.text);
                        }
                        $li.append($a);
                        if (item.disabled) {
                            $li.on('click', function ($event) {
                                $event.preventDefault();
                            });
                            $li.addClass('disabled');
                        } else {
                            $li.on('click', function ($event) {
                                $event.preventDefault();
                                $scope.$apply(function () {
                                    $(event.currentTarget).removeClass('context');
                                    $contextMenu.remove();
                                    item.callback($scope.media);
                                });
                            });
                        }
                        break;
                    case 'header':
                        $li.html(item.text);
                        $li.addClass('dropdown-header');
                        break;
                    default :
                        $li.html((angular.isDefined(item.icon) ? '<i class="' + item.icon + '"></i> ' : '') + item.text);
                        $li.addClass('dropdown-text');
                }
            }
            $ul.append($li);
        });
        $ul.css({
            display: 'block',
            position: 'absolute',
            left: '-500px',
            top: '-1000px'
        });
        $contextMenu.append($ul);
        $(document).find('body').append($contextMenu);
        var left = event.pageX,
            top = event.pageY;
        //$ul.prop('offsetWidth')
        if (event.pageY + $ul.prop('offsetHeight') > document.body.offsetHeight) {
            top = event.pageY - $ul.prop('offsetHeight');
        }
        if (event.pageX + $ul.prop('offsetWidth') > document.body.offsetWidth) {
            left = event.pageX - $ul.prop('offsetWidth');
        }
        $ul.css({
            left: left + 'px',
            top: top + 'px'
        });
        $contextMenu.on("mousedown", function (e) {
            if ($(e.target).hasClass('vns-media-context-menu')) {
                $(event.currentTarget).removeClass('context');
                $contextMenu.remove();
            }
        }).on('contextmenu', function (event) {
            $(event.currentTarget).removeClass('context');
            event.preventDefault();
            $contextMenu.remove();
        });
    };
    return function ($scope, element) {
        element.on('contextmenu', function (event) {
            event.stopPropagation();
            $scope.$apply(function () {
                event.preventDefault();
                renderContextMenu($scope, event);
            });
        });
    };
});

VnSapp.directive('vnsMediaThumb', function () {
    return function ($scope, element, attrs) {
        element.on('load', function (event) {
            if (element[0].naturalHeight > element[0].naturalWidth) {
                element.addClass('portrait');
            }
        });
    };
});

VnSapp.directive('vnsMediaThumbFolder', function () {
    return {
        restrict: 'AE',
        scope: {
            type: '@'
        },
        controller: function ($scope, Thumbs) {
            switch ($scope.type) {
                case 'back':
                    $scope.src = Thumbs.back;
                    break;
                case 'empty':
                    $scope.src = Thumbs.folderEmpty;
                    break;
                default :
                    $scope.src = Thumbs.folder;
            }
        },
        template: '<img ng-src="{{src}}" />'
    };
});

VnSapp.directive('vnsMediaInput', function (vnsMedia) {
    return {
        restrict: 'AE',
        scope: {
            config: '=mediaConfig',
            images: '=ngModel'
        },
        controller: function ($scope) {
            $scope.defaultConfig = angular.extend({}, {
                type: 'image',
                single: false
            }, $scope.config);
            if ($scope.defaultConfig.single == true) {
                $scope.thumb = $scope.images;
                var unbindWatch = $scope.$watch('images', function (newVal, oldVal) {
                    if ($scope.thumb == null && $scope.images != null) {
                        $scope.thumb = $scope.images;
                        unbindWatch();
                    }
                });
            }
            $scope.chosen = function () {
                var dlg = vnsMedia.dialog($scope.defaultConfig);
                dlg.result.then(function (medias) {
                    if ($scope.defaultConfig.single == false) {
                        var images = $scope.images == null ? medias : $scope.images.concat(medias),
                            temp = [];
                        $scope.images = images.filter(function (elm) {
                            if (temp.indexOf(elm.id) === -1) {
                                temp.push(elm.id);
                                return true;
                            } else {
                                return false;
                            }
                        }).map(function (val) {
                            return {
                                id: val.id,
                                name: val.name,
                                thumb: val.thumb,
                                source: val.source
                            }
                        });
                        images = undefined;
                        temp = undefined;
                    } else {
                        $scope.thumb = medias[0].thumb;
                        $scope.images = medias[0].source;
                    }
                });
            };

            $scope.remove = function (img) {
                if ($scope.defaultConfig.single == false) {
                    $scope.images = $scope.images.filter(function (elm) {
                        return elm.id != img.id;
                    })
                } else {
                    $scope.thumb = null;
                    $scope.images = null;
                }
            }
        },
        templateUrl: 'vnsMedia/input.html'
    };
});

VnSapp.controller('vnsMediaCtrl', function ($rootScope, $scope, $http, $uibModal, $window, vnsMedia, Dialog, Thumbs, Notification) {
    $scope.defaultConfig = angular.extend({}, {
        type: undefined,
        single: false,
        modal: false,
        upload: true,
        createFolder: true,
        delete: true,
        rename: true,
        folder: 0,
        callback: function () {
        }
    }, $scope.config);

    $scope.currentFolder = $scope.defaultConfig.folder;
    $scope.selected = 0;
    $scope.files = 0;
    $scope.breadcrumb = [];
    $scope.folders = 0;
    $scope.loadMedias = function () {
        $scope.isLoading = true;
        $scope.files = 0;
        $scope.folders = 0;
        vnsMedia.get($scope.currentFolder, $scope.defaultConfig.type).then(function (response) {
            $scope.medias = response.data.medias;
            $scope.folders = response.data.folders;
            $scope.files = response.data.files;
            $scope.isLoading = false;
        }, function (response) {

        });
    };
    $scope.thumbs = Thumbs;
    $scope.selectMedias = function () {
        for (var key in $scope.medias) {
            if ($scope.medias[key].type != 'folder') {
                $scope.medias[key].selected = true;
                $scope.selected += 1;
            }
        }
    };
    $scope.unselectMedias = function () {
        for (var key in $scope.medias) {
            $scope.medias[key].selected = false;
        }
        $scope.selected = 0;
    };
    $scope.deleteMedias = function () {
        Dialog.confirm(__('Are you sure you want to delete the selected file?')).result.then(function () {
            var deletes = [];
            for (var key in $scope.medias) {
                if ($scope.medias[key].selected == true) {
                    deletes.push($scope.medias[key].id);
                }
            }

            $http.post(API_URL + '/media/deletes', {
                ids: deletes,
                callback: $scope.defaultConfig.callback
            }).then(function (response) {
                for (var key in $scope.medias) {
                    if (deletes.indexOf($scope.medias[key].id) != -1) {
                        delete $scope.medias[key];
                    }
                }
                $scope.medias = $scope.medias.filter(function (a) {
                    return typeof a !== 'undefined';
                });
                $scope.files -= deletes.length;
                $scope.selected = 0;
                Notification.success(__('Delete successfully'));
            }, function () {
                Notification.error(__('Delete failed'));
            });
        });
    };
    $scope.clickMedia = function (index, type) {
        switch (type) {
            case 'folder':
                $scope.currentFolder = $scope.medias[index].id;
                $scope.breadcrumb.push({id: $scope.medias[index].id, name: $scope.medias[index].name});
                $scope.loadMedias();
                break;
            default:
                if ($scope.medias[index].selected) {
                    $scope.selected -= 1;
                    $scope.medias[index].selected = false;
                }
                else {
                    if ($scope.defaultConfig.single == true) {
                        $scope.unselectMedias();
                    }
                    $scope.selected += 1;
                    $scope.medias[index].selected = true;
                }
        }
    };
    $scope.clickBack = function () {
        $scope.breadcrumb.pop();
        if ($scope.breadcrumb.length > 0) {
            $scope.currentFolder = $scope.breadcrumb[$scope.breadcrumb.length - 1].id;
        } else {
            $scope.currentFolder = 0;
        }
        $scope.loadMedias();
    };
    $scope.clickBreadcrumb = function (index) {
        if (index == -1) {
            $scope.currentFolder = 0;
            $scope.breadcrumb = [];
            $scope.loadMedias();
        }
        else {
            $scope.currentFolder = $scope.breadcrumb[index].id;
            $scope.breadcrumb = $scope.breadcrumb.slice(0, index + 1);
            $scope.loadMedias();
        }
    };
    $scope.uploadMedias = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'vnsMedia/modal/upload.html',
            controller: 'vnsMediaUploadCtrl',
            backdrop: 'static',
            resolve: {
                data: $scope
            }
        });
    };
    $scope.createFolder = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'vnsMedia/modal/folder.html',
            controller: 'vnsMediaCreateFolderCtrl',
            backdrop: 'static',
            resolve: {
                data: $scope
            }
        });
    };
    $scope.loadMedias();

    if ($scope.defaultConfig.modal == true) {
        var styleWatch = $rootScope.$watch($rootScope.getWindowDimensions, function (newValue, oldValue) {
            $rootScope.modalMediaStyle = {
                'max-height': (newValue.h - (newValue.w < 768 ? 157 : 217)) + 'px'
            };
        }, true);

        $scope.$on('$destroy', function () {
            styleWatch();
        });
    }
});

VnSapp.controller('vnsMediaModalCtrl', function ($rootScope, $scope, $uibModalInstance, config) {
    $scope.medias = [];
    $scope.config = angular.extend({}, config, {modal: true});
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };

    $scope.ok = function () {
        $uibModalInstance.close($scope.selectedMedias);
    };
    var mediaWatch = $scope.$watch('medias | json', function (newValue, oldValue) {
        $scope.selectedMedias = [];
        if ($scope.medias.length > 0) {
            for (var key in $scope.medias) {
                if ($scope.medias[key].selected) {
                    $scope.selectedMedias.push($scope.medias[key]);
                }
            }
        }
    });
    $scope.$on('$destroy', function () {
        mediaWatch();
    });
});

VnSapp.controller('vnsMediaUploadCtrl', function ($scope, $uibModalInstance, data) {
    $scope.currentFolder = data.currentFolder;
    //$scope.callback = data.config.callback;
    $scope.processing = false;
    $scope.success = function (media) {
        data.files += 1;
        if (angular.isDefined(data.config) && !angular.isDefined(data.config.single)) {
            media.selected = true;
        }
        data.medias.push(media);
    };
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };

});

VnSapp.controller('vnsMediaShowURLCtrl', function ($scope, $uibModalInstance, data) {
    $scope.data = data;
    $scope.success = null;
    $scope.onTextClick = function ($event) {
        $event.target.select();
    };
    $scope.copied = function () {
        $scope.success = true;
    };
    $scope.error = function () {
        $scope.success = false;
    };
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };
});

VnSapp.controller('vnsMediaRenameCtrl', function ($scope, $uibModalInstance, vnsMedia, Notification, data) {
    $scope.data = angular.copy(data);
    $scope.rename = function () {
        if (data.name == $scope.data.name) {
            $uibModalInstance.dismiss('close');
        }
        else {
            vnsMedia.rename($scope.data).then(function () {
                data.name = $scope.data.name;
                Notification.success(__('Rename successfully'));
            }, function () {
                Notification.error(__('Rename failed'));
            });
            $uibModalInstance.close('rename');
        }
    };
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };
});

VnSapp.controller('vnsMediaViewCtrl', function ($rootScope, $scope, $uibModalInstance, data) {
    $scope.data = data;
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };
});

VnSapp.controller('vnsMediaCreateFolderCtrl', function ($scope, $uibModalInstance, $http, data, Notification) {
    $scope.name = __('New folder');
    $scope.create = function () {
        $http.post(API_URL + '/media/folder', {
            name: $scope.name,
            folder: data.currentFolder
        }).then(function (response) {
            data.folders += 1;
            data.medias.unshift(response.data);
            Notification.success(__('Create successfully'));
        }, function () {
            Notification.error(__('Create failed'));
        });
        $uibModalInstance.close('create');
    };
    $scope.close = function () {
        $uibModalInstance.dismiss('close');
    };
});

VnSapp.controller('DashboardCtrl', function ($rootScope) {
    $rootScope.siteTitle = __('Dashboard');
});

VnSapp.controller('MediaCtrl', function ($rootScope) {
    $rootScope.siteTitle = __('Media');
});

VnSapp.controller('UserCtrl', function ($rootScope, $scope, vnsTableParams, $resource, $http, Dialog, $uibModal, Notification) {

    $rootScope.siteTitle = __('User');
    $scope.titleId = __('Id');
    $scope.titleAvatar = __('Avatar');
    $scope.titleUser = __('User');
    $scope.titleRegistered = __('Registered');
    $scope.titleStatus = __('Status');
    $scope.titleEmail = __('Email / Phone');
    var User = $resource(API_URL + '/user/:id', {id: '@id'}, {
        update: {
            method: 'PUT'
        }
    });
    var tableParams = $scope.tableParams = new vnsTableParams({
        columns: {
            id: {
                label: __('Id'),
                type: 'fixed',
                filter: 'number',
                sortable: true
            },
            avatar: {
                label: __('Avatar'),
                type: 'image',
                value: '/avatar/:id/50'
            },
            display_name: {
                label: __('User'),
                filter: 'text',
                size: 18,
                text: 'info',
                sortable: true,
                and: {
                    group_name: {
                        format: function (value) {
                            return __(value);
                        }
                    }
                }
            },
            created_at: {
                label: __('Registered'),
                type: 'datetime'
            },
            email: {
                label: __('Email / Phone'),
                icon: 'fa fa-envelope-o iw-20',
                text: 'info',
                sortable: true,
                and: {
                    phone: {
                        icon: 'fa fa-phone iw-20',
                        text: 'info'
                    }
                }
            },
            status: {
                label: __('Status'),
                type: 'status',
                filter: {
                    'Yes': 1,
                    'No': 0
                },
                action: function (row) {
                    $scope.toggleStatus(row);
                }
            }
        },
        actions: [
            {
                label: __('View'),
                icon: 'fa fa-eye fa-fw',
                callback: function (row) {
                    $scope.view(row);
                }
            },
            {
                label: __('Edit'),
                icon: 'fa fa-pencil fa-fw',
                callback: function (row) {
                    $scope.edit(row);
                }
            },
            'divider',
            {
                label: __('Delete'),
                icon: 'fa fa-trash-o fa-fw',
                callback: function (row) {
                    $scope.delete(row);
                }
            }
        ],
        getData: function (params) {
            return User.query(params.url(), function (data, headers) {
                params.setTotal(headers('total'));
                return data;
            });
        }
    });

    $scope.view = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'user/view.html',
            controller: function ($scope, $uibModalInstance) {
                User.get({id: row.id}, function (user) {
                    $scope.user = user;
                });
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            }
        });
    };

    $scope.avatar = function (row) {
        var input = document.getElementById('avatar-input');
        var handleFileSelect = function (evt) {
            var formData = new FormData();
            formData.append('file', evt.target.files[0]);
            formData.append('user', row.id);
            $http.post(API_URL + '/user/avatar', formData, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function (response) {
                if (response.data == false) {
                    Notification.error(__('Changed avatar failed'));
                } else {
                    row.avatarTime = new Date().getTime();
                    Notification.success(__('Changed avatar successfully'));
                }
            }, function (response) {
                Notification.error(__('Changed avatar failed'));
            });
            input.removeEventListener('change', handleFileSelect);
        };
        input.value = null;
        input.addEventListener('change', handleFileSelect);
        input.click();
    };

    $scope.new = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'user/new.html',
            controller: function ($scope, $uibModalInstance, $http) {
                $http.get(API_URL + '/group/all').then(function (response) {
                    $scope.groups = response.data;
                });
                $scope.user = {
                    email: null,
                    phone: null,
                    display_name: null,
                    password: null,
                    password_confirmation: null,
                    password_send: false,
                    status: false,
                    group_code: null
                };
                $scope.save = function () {
                    var $user = new User($scope.user);
                    $user.$save(function (res) {
                        tableParams.reload();
                        $uibModalInstance.dismiss('close');
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                };
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            },
            backdrop: 'static'
        });
    };

    $scope.edit = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'user/edit.html',
            controller: function ($scope, $uibModalInstance, $http) {
                $http.get(API_URL + '/group/all').then(function (response) {
                    $scope.groups = response.data;
                });
                $scope.user = angular.copy(row);
                $scope.save = function () {
                    var $user = angular.copy($scope.user);
                    $user.$update(function (res) {
                        row.email = angular.copy($scope.user.email);
                        row.phone = angular.copy($scope.user.phone);
                        row.display_name = angular.copy($scope.user.display_name);
                        row.group_code = angular.copy($scope.user.group_code);
                        row.status = angular.copy($scope.user.status);
                        $uibModalInstance.dismiss('close');
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                };
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            },
            backdrop: 'static'
        });
    };

    $scope.delete = function (row) {
        Dialog.confirm(__('Are you sure you want to delete <b>:name</b>?', {name: row.display_name}))
            .result.then(function () {
            row.$delete(function (res) {
                if (res == false) {
                    Notification.error(__('Delete failed'));
                } else {
                    tableParams.reload();
                    Notification.success(__('Delete successfully'));
                }
            })
        });
    };

    $scope.toggleStatus = function (row) {
        var $user = angular.copy(row);
        $user.status = !row.status;
        $user.toggleStatus = true;
        $user.$update(function (res) {
            row.status = !row.status;
            Notification.success(__('Saved successfully'));
        }, function (xhr) {
            if (xhr.status == 422) {
                var validatorError = [];
                for (key in xhr.data) {
                    validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                }
                Notification.error(validatorError.join('<br>'));
            }
        });
    };
});

VnSapp.controller('GroupCtrl', function ($rootScope, $scope, $q, vnsTableParams, $resource, $http, Dialog, $uibModal, Notification) {

    $rootScope.siteTitle = __('Group');
    $scope.titleCode = __('Code');
    $scope.titleName = __('Name');
    $scope.titleUser = __('User');
    $scope.titleStatus = __('Status');
    var Group = $resource(API_URL + '/group/:code', {code: '@code'}, {
        update: {
            method: 'PUT'
        }
    });

    var tableParams = $scope.tableParams = new vnsTableParams({
        columns: {
            code: {
                label: __('Code')
            },
            name: {
                label: __('Name'),
                translate: true
            },
            users: {
                label: __('Users')
            },
            status: {
                label: __('Status'),
                type: 'status',
                action: function (row) {
                    $scope.toggleStatus(row);
                }
            }
        },
        actions: [
            {
                label: __('Edit'),
                icon: 'fa fa-pencil fa-fw',
                callback: function (row) {
                    $scope.edit(row);
                }
            },
            {
                label: __('Delete'),
                icon: 'fa fa-trash-o fa-fw',
                callback: function (row) {
                    $scope.delete(row);
                }
            }
        ],
        getData: function (params) {
            return Group.query(params.url(), function (data) {
                return data;
            });
        }
    });

    $scope.new = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'group/new.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.group = {
                    code: null,
                    name: null,
                    description: null,
                    permissions: null,
                    status: true
                };
                $scope.save = function () {
                    $http.post(API_URL + '/group', $scope.group).then(function (response) {
                        reload();
                        $uibModalInstance.dismiss('close');
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                };
                $scope.permissions = [];
                $scope.$watchCollection('permissions', function (newValue, oldValue) {
                    $scope.group.permissions = [];
                    for (var key in newValue) {
                        $scope.group.permissions.push(newValue[key].code);
                    }
                });
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
                $scope.queryPermissions = function (query) {
                    return $http.get(API_URL + '/group/permissions/' + query);
                };
            },
            backdrop: 'static'
        });
    };

    $scope.edit = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'group/edit.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.group = angular.copy(row);
                $scope.save = function () {
                    var $group = angular.copy($scope.group);
                    $group.$update(function (res) {
                        row.name = angular.copy($scope.group.name);
                        row.description = angular.copy($scope.group.description);
                        row.permissions = angular.copy($scope.group.permissions);
                        row.status = angular.copy($scope.group.status);
                        $uibModalInstance.dismiss('close');
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                };
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
                var tempPermissions = angular.copy($scope.group.permissions);
                if (tempPermissions == null) {
                    tempPermissions = [];
                }
                $scope.permissions = [];
                $scope.$watchCollection('permissions', function (newValue, oldValue) {
                    $scope.group.permissions = [];
                    for (var key in newValue) {
                        $scope.group.permissions.push(newValue[key].code);
                    }
                });
                var allPermissions = [];
                $http.get(API_URL + '/group/permissions').then(function (response) {
                    allPermissions = response.data;
                    $scope.permissions = allPermissions.reduce(function (all, item) {
                        if (tempPermissions.indexOf(item.code) !== -1) {
                            item.name = __(item.name);
                            all.push(item);
                        }
                        return all;
                    }, []);
                });
                $scope.queryPermissions = function (query) {
                    var deferred = $q.defer(),
                        permissions = allPermissions.reduce(function (all, item) {
                            var name = __(item.name);
                            var patt = new RegExp(query, 'i');
                            if (patt.test(name) || patt.test(item.name) || patt.test(item.code)) {
                                item.name = name;
                                all.push(item);
                            }
                            return all;
                        }, []);
                    deferred.resolve(permissions);
                    return deferred.promise;
                };
            },
            backdrop: 'static'
        });
    };

    $scope.delete = function (row) {
        if (['administrator', 'member'].indexOf(row.code) !== -1) {
            Notification.warning(__("You can't delete <b>:name</b>!", {name: __(row.name)}));
        } else {
            Dialog.confirm(__('Are you sure you want to delete <b>:name</b>?', {name: __(row.name)}))
                .result.then(function () {
                row.$delete(function (res) {
                    if (res.success == true) {
                        reload();
                        Notification.success(__('Delete successfully'));
                    } else {
                        Notification.error(__('Delete failed'));
                    }
                })
            });
        }
    };

    $scope.toggleStatus = function (row) {
        if (['administrator', 'member'].indexOf(row.code) !== -1) {
            Notification.warning(__("You can't disabled <b>:name</b>!", {name: __(row.name)}));
        } else {
            var $group = angular.copy(row);
            $group.status = !row.status;
            $group.toggleStatus = true;
            $group.$update(function (res) {
                row.status = !row.status;
                Notification.success(__('Saved successfully'));
            }, function (xhr) {
                if (xhr.status == 422) {
                    var validatorError = [];
                    for (key in xhr.data) {
                        validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                    }
                    Notification.error(validatorError.join('<br>'));
                }
            });
        }
    };
});

VnSapp.controller('ConfigCtrl', function ($rootScope, $scope, $http, Dialog, Notification) {
    $rootScope.siteTitle = __('Config');
    $http.get(API_URL + '/config').then(function (response) {
        $scope.tabs = response.data;
    });
    var configData = function (tabs) {
        var datas = {};
        for (tabIndex in tabs) {
            for (configKey in tabs[tabIndex].data) {
                datas[configKey] = tabs[tabIndex].data[configKey].value;
            }
        }
        return datas;
    };
    $scope.updateConfig = function () {
        var dlg = Dialog.confirm('Are you sure you want to save changes?');
        dlg.result.then(function () {
            $http.post(API_URL + '/config', configData(angular.copy($scope.tabs))).then(function (response) {
                if (response.status == 200) {
                    if (response.data.success) {
                        Notification.success(__('Saved successfully'));
                    } else {
                        Notification.error(response.data.message);
                    }
                } else {
                    Notification.error(__('Unknown error'));
                }
            });
        }, function (btn) {
            //alert('no');
        });
    }
});

VnSapp.controller('ModuleCtrl', function ($rootScope, $scope, $uibModal, $location, vnsTableParams, $resource, Dialog, Notification, $http, $filter) {
    $rootScope.siteTitle = __('Modules');
    var Module = $resource(API_URL + '/module/:nameBase64', {nameBase64: '@nameBase64'}, {
        update: {
            method: 'PUT'
        }
    });

    var tableParams = $scope.tableParams = new vnsTableParams({
        columns: {
            image: {
                label: __('Image'),
                type: 'image'
            },
            displayName: {
                label: __('Name'),
                size: 18,
                text: 'info',
                and: {
                    authors: {
                        format: function (value) {
                            return __('Author') +': '+$filter('parseAuthor')(value);
                        }
                    },
                    version: {
                        format: function (value) {
                            return __('Version') +': '+value;
                        }
                    }
                }
            },
            description: {
                label: __('Description')
            },
            enabled: {
                label: __('Status'),
                type: 'status',
                action: function (row) {
                    $scope.toggleStatus(row);
                }
            }
        },
        actions: [
            {
                label: __('Detail'),
                icon: 'fa fa-eye fa-fw',
                callback: function (row) {
                    $scope.view(row);
                }
            },
            {
                label: __('Delete'),
                icon: 'fa fa-trash-o fa-fw',
                callback: function (row) {
                    $scope.delete(row);
                }
            }
        ],
        getData: function (params) {
            return Module.query(params.url(), function (data) {
                return data;
            });
        }
    });

    $scope.view = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'module/view.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.module = row;
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            }
        });
    };

    $scope.new = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'module/new.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.isInstalling = false;
                $scope.isChecking = false;
                $scope.stepInstall = 0;
                $scope.info = null;
                $scope.error = null;
                $scope.github = null;
                $scope.check = function () {
                    $scope.isChecking = true;
                    $scope.error = null;
                    $scope.info = null;
                    if (/^https:\/\/github\.com\/[^\/]+\/[^\/]+/.test($scope.github)) {
                        $http.post(API_URL + '/module/check', {github: $scope.github}).then(function (response) {
                            if (response.data.name == undefined) {
                                $scope.error = __('GitHub repository is not module for VnSource');
                            } else {
                                $scope.info = response.data;
                            }
                            $scope.isChecking = false;
                        }, function (response) {
                            $scope.isChecking = false;
                            $scope.error = __('Unknown error');
                        });
                    } else {
                        $scope.error = __('Please enter URL GitHub repository');
                    }
                };
                $scope.install = function () {
                    $scope.isInstalling = true;
                    $scope.stepInstall = 1;
                    $http.post(API_URL + '/module/download', {name: $scope.info.name}).then(function (responseDownload) {
                        if (responseDownload.data == true) {
                            $scope.stepInstall = 2;
                            $http.post(API_URL + '/module/unpack', {
                                name: $scope.info.name,
                                version: $scope.info.version
                            }).then(function (responseUnpack) {
                                if (responseUnpack.data != false) {
                                    $scope.stepInstall = 3;
                                    var $module = new Module;
                                    $module.name = responseUnpack.data.name;
                                    $module.$save(function (res) {
                                        $scope.stepInstall = 4;
                                        $scope.isInstalling = false;
                                        $scope.info = res;
                                        tableParams.reload();
                                    }, function (response) {
                                        $scope.stepInstall = 5;
                                        $scope.isInstalling = false;
                                    });
                                } else {
                                    $scope.stepInstall = 5;
                                    $scope.isInstalling = false;
                                }
                            }, function (response) {
                                $scope.stepInstall = 5;
                                $scope.isInstalling = false;
                            });
                        } else {
                            $scope.stepInstall = 5;
                            $scope.isInstalling = false;
                        }
                    }, function (response) {
                        $scope.stepInstall = 5;
                        $scope.isInstalling = false;
                    });
                };
                $scope.closeAlert = function () {
                    $scope.error = null;
                };
                $scope.activate = function () {
                    var $module = new Module({nameBase64: $scope.info.nameBase64, enabled: true, toggleStatus: true});
                    $module.$update(function (res) {
                        tableParams.reload();
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                    $uibModalInstance.dismiss('close');
                };
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            },
            backdrop: 'static'
        });
    };

    $scope.delete = function (row) {
        Dialog.confirm(__('Are you sure you want to uninstall <b>:displayName :version</b>?', row))
            .result.then(function () {
            row.$delete(function (res) {
                if (res == false) {
                    Notification.error(__('Uninstall failed'));
                } else {
                    tableParams.reload();
                    Notification.success(__('Uninstall successfully'));
                }
            })
        });
    };

    $scope.toggleStatus = function (row) {
        var $module = angular.copy(row);
        $module.enabled = !row.enabled;
        $module.toggleStatus = true;
        $module.$update(function (res) {
            row.enabled = !row.enabled;
            Notification.success(__('Saved successfully'));
        }, function (xhr) {
            if (xhr.status == 422) {
                var validatorError = [];
                for (key in xhr.data) {
                    validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                }
                Notification.error(validatorError.join('<br>'));
            }
        });
    };
});

VnSapp.controller('ThemeCtrl', function ($rootScope, $scope, $uibModal, $location, vnsTableParams, $resource, Dialog, Notification, $http) {
    $rootScope.siteTitle = __('Themes');
    var Theme = $resource(API_URL + '/theme/:nameBase64', {nameBase64: '@nameBase64'}, {
        update: {
            method: 'PUT'
        }
    });


    var tableParams = $scope.tableParams = new vnsTableParams({
        columns: {
            image: {
                label: __('Image'),
                type: 'image'
            },
            displayName: {
                label: __('Name'),
                size: 18,
                text: 'info',
                filter: 'text',
                and: {
                    authors: {
                        format: function (value) {
                            return __('Author') +': '+$filter('parseAuthor')(value);
                        }
                    },
                    version: {
                        format: function (value) {
                            return __('Version') +': '+value;
                        }
                    }
                }
            },
            description: {
                label: __('Description')
            },
            default: {
                label: __('Default'),
                type: 'fixed',
                format: function (value) {
                    return value == true?'<i class="fa fa-check fa-lg text-success"></i>':'';
                }
            }
        },
        actions: [
            {
                label: __('Detail'),
                icon: 'fa fa-eye fa-fw',
                callback: function (row) {
                    $scope.view(row);
                }
            },
            {
                label: __('Set default'),
                icon: 'fa fa-check fa-fw',
                callback: function (row) {
                    $scope.view(row);
                }
            },
            {
                label: __('Widget'),
                icon: 'fa fa-clone fa-fw',
                callback: function (row) {
                    $scope.view(row);
                }
            },
            'divider',
            {
                label: __('Uninstall'),
                icon: 'fa fa-trash-o fa-fw',
                callback: function (row) {
                    $scope.delete(row);
                }
            }
        ],
        getData: function (params) {
            return Theme.query(params.url(), function (data) {
                return data;
            });
        }
    });

    $scope.view = function (row) {
        $uibModal.open({
            animation: true,
            templateUrl: 'module/view.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.module = row;
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            }
        });
    };

    $scope.new = function () {
        $uibModal.open({
            animation: true,
            templateUrl: 'theme/new.html',
            controller: function ($scope, $uibModalInstance) {
                $scope.isInstalling = false;
                $scope.isChecking = false;
                $scope.stepInstall = 0;
                $scope.info = null;
                $scope.error = null;
                $scope.github = null;
                $scope.check = function () {
                    $scope.isChecking = true;
                    $scope.error = null;
                    $scope.info = null;
                    if (/^https:\/\/github\.com\/[^\/]+\/[^\/]+/.test($scope.github)) {
                        $http.post(API_URL + '/theme/check', {github: $scope.github}).then(function (response) {
                            if (response.data.name == undefined) {
                                $scope.error = __('GitHub repository is not theme for VnSource');
                            } else {
                                $scope.info = response.data;
                            }
                            $scope.isChecking = false;
                        }, function (response) {
                            $scope.isChecking = false;
                            $scope.error = __('Unknown error');
                        });
                    } else {
                        $scope.error = __('Please enter URL GitHub repository');
                    }
                };
                $scope.install = function () {
                    $scope.isInstalling = true;
                    $scope.stepInstall = 1;
                    $http.post(API_URL + '/theme/download', {name: $scope.info.name}).then(function (responseDownload) {
                        if (responseDownload.data == true) {
                            $scope.stepInstall = 2;
                            $http.post(API_URL + '/theme/unpack', {
                                name: $scope.info.name,
                                version: $scope.info.version
                            }).then(function (responseUnpack) {
                                if (responseUnpack.data != false) {
                                    $scope.stepInstall = 3;
                                    var $theme = new Theme;
                                    $theme.name = responseUnpack.data.name;
                                    $theme.$save(function (res) {
                                        $scope.stepInstall = 4;
                                        $scope.isInstalling = false;
                                        $scope.info = res;
                                        tableParams.reload();
                                    }, function (response) {
                                        $scope.stepInstall = 5;
                                        $scope.isInstalling = false;
                                    });
                                } else {
                                    $scope.stepInstall = 5;
                                    $scope.isInstalling = false;
                                }
                            }, function (response) {
                                $scope.stepInstall = 5;
                                $scope.isInstalling = false;
                            });
                        } else {
                            $scope.stepInstall = 5;
                            $scope.isInstalling = false;
                        }
                    }, function (response) {
                        $scope.stepInstall = 5;
                        $scope.isInstalling = false;
                    });
                };
                $scope.closeAlert = function () {
                    $scope.error = null;
                };
                $scope.default = function () {
                    var $theme = new Theme({nameBase64: $scope.info.nameBase64, toggleStatus: true});
                    $theme.$update(function (res) {
                        tableParams.reload();
                        Notification.success(__('Saved successfully'));
                    }, function (xhr) {
                        if (xhr.status == 422) {
                            var validatorError = [];
                            for (key in xhr.data) {
                                validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                            }
                            Notification.error(validatorError.join('<br>'));
                        }
                    });
                    $uibModalInstance.dismiss('close');
                };
                $scope.close = function () {
                    $uibModalInstance.dismiss('close');
                };
            },
            backdrop: 'static'
        });
    };

    $scope.delete = function (row) {
        Dialog.confirm(__('Are you sure you want to uninstall <b>:displayName :version</b>?', row))
            .result.then(function () {
            row.$delete(function (res) {
                if (res == false) {
                    Notification.error(__('Uninstall failed'));
                } else {
                    tableParams.reload();
                    Notification.success(__('Uninstall successfully'));
                }
            })
        });
    };

    $scope.toggleStatus = function (row) {
        var $theme = angular.copy(row);
        $theme.toggleStatus = true;
        $theme.$update(function (res) {
            row.default = true;
            Notification.success(__('Saved successfully'));
        }, function (xhr) {
            if (xhr.status == 422) {
                var validatorError = [];
                for (key in xhr.data) {
                    validatorError.push(key + ': ' + (typeof xhr.data[key] == 'string' ? xhr.data[key] : xhr.data[key][0]));
                }
                Notification.error(validatorError.join('<br>'));
            }
        });
    };
});
