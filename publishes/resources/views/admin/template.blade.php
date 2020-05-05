<script type="text/ng-template" id="autocomplete/icon.html">
    <div class="left-panel">
        <i class="@{{data.icon}}"></i>
    </div>
    <div class="right-panel">
        <span ng-bind-html="$highlight($getDisplayText())"></span>
    </div>
</script>
<script type="text/ng-template" id="autocomplete/image.html">
    <div class="left-panel">
        <img ng-src="@{{data.image}}"/>
    </div>
    <div class="right-panel">
        <span ng-bind-html="$highlight($getDisplayText())"></span>
    </div>
</script>
<script type="text/ng-template" id="autocomplete/user.html">
    <div class="left-panel">
        <img ng-src="avatar/@{{data.id}}/40"/>
    </div>
    <div class="right-panel">
        <span class="display-block" ng-bind-html="$highlight($getDisplayText())"></span>
        <i ng-if="data.email">@{{data.email}} </i>
        <i ng-if="data.phone">@{{data.phone}}</i>
    </div>
</script>
<script type="text/ng-template" id="tag/icon.html">
    <div class="tag-template">
        <div>
            <i class="@{{data.icon}}" ng-if="data.icon"></i>
        </div>
        <div>
            <span>@{{$getDisplayText()}}</span>
            <a class="remove-button" ng-click="$removeTag()">&#10006;</a>
        </div>
    </div>
</script>
<script type="text/ng-template" id="tag/image.html">
    <div class="tag-template">
        <div>
            <img ng-src="@{{data.image}}" ng-if="data.image"/>
        </div>
        <div>
            <span>@{{$getDisplayText()}}</span>
            <a class="remove-button" ng-click="$removeTag()">&#10006;</a>
        </div>
    </div>
</script>
<script type="text/ng-template" id="tag/user.html">
    <div class="tag-template">
        <div>
            <img ng-src="avatar/@{{data.id}}/32"/>
        </div>
        <div>
            <span>@{{$getDisplayText()}}</span>
            <a class="remove-button" ng-click="$removeTag()">&#10006;</a>
        </div>
    </div>
</script>
<script type="text/ng-template" id="filters/disabled.html">
    <span class="glyphicon glyphicon-minus"></span>
</script>
<script type="text/ng-template" id="filters/status.html">
    <select class="form-control" ng-model="params.filter()[name]">
        <option></option>
        <option value="true">{{__('Yes')}}</option>
        <option value="false">{{__('No')}}</option>
    </select>
</script>
<script type="text/ng-template" id="dropdown-select/multi.html">
    <div class="dropdown dropdown-select" ng-class="{open: open}">
        <button type="button" class="btn btn-default dropdown-toggle" ng-disabled="disabled" ng-click="openDropdown()">
            <span ng-bind-html="label"></span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:void(0)" ng-click="selectAll()"><i
                            class="fa fa-check-square"></i>{{__('Check All')}}</a></li>
            <li><a href="javascript:void(0)" ng-click="deselectAll()"><i
                            class="fa fa-square"></i>{{__('Uncheck All')}}</a></li>
            <li class="divider"></li>
            <li ng-repeat="option in options">
                <span ng-if="option.id == undefined" ng-bind-html="option.name" class="dropdown-header"></span>
                <a ng-if="option.id != undefined" href="javascript:void(0)" ng-click="toggleSelectItem(option)" ng-class="getClassSpace(option)">
                    <i ng-class="getClassIcon(option)"></i>
                    <span ng-bind-html="option.name"></span>
                </a>
            </li>
        </ul>
    </div>
</script>
<script type="text/ng-template" id="dropdown-select/single.html">
    <div class="dropdown dropdown-select" ng-class="{open: open}">
        <button type="button" class="btn btn-default dropdown-toggle" ng-disabled="disabled" ng-click="openDropdown()">
            <span ng-bind-html="label"></span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li ng-repeat="option in options">
                <span ng-if="option.id == undefined" ng-bind-html="option.name" class="dropdown-header"></span>
                <a ng-if="option.id != undefined" href="javascript:void(0)" ng-click="toggleSelectItem(option)" ng-class="getClassSpace(option)">
                    <i ng-class="getClassIcon(option)"></i>
                    <span ng-bind-html="option.name"></span>
                </a>
            </li>
        </ul>
    </div>
</script>
<script type="text/ng-template" id="dialog/notify.html">
    <div class="modal-header dialog-header-notify">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title text-info">
            <span class="fa fa-exclamation-circle"></span>
            <span ng-bind-html="header | translate"></span>
        </h4>
    </div>
    <div class="modal-body text-info" ng-bind-html="msg | translate"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Ok') }}</button>
    </div>
</script>
<script type="text/ng-template" id="dialog/confirm.html">
    <div class="modal-header dialog-header-confirm">
        <button type="button" class="close" ng-click="no()">&times;</button>
        <h4 class="modal-title">
            <span class="fa fa-check"></span>
            <span ng-bind-html="header | translate"></span>
        </h4>
    </div>
    <div class="modal-body" ng-bind-html="msg | translate"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="yes()" >{{ __('Yes') }}</button>
        <button type="button" class="btn btn-primary" ng-click="no()" >{{ __('No') }}</button>
    </div>
</script>
<script type="text/ng-template" id="dialog/error.html">
    <div class="modal-header dialog-header-error">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title text-danger">
            <span class="fa fa-exclamation-triangle"></span>
            <span ng-bind-html="header | translate"></span>
        </h4>
    </div>
    <div class="modal-body text-danger" ng-bind-html="msg | translate"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="dialog/wait.html">
    <div class="modal-header dialog-header-wait">
        <h4 class="modal-title text-info">
            <span class="fa fa-clock-o"></span>
            <span ng-bind-html="header | translate"></span>
        </h4>
    </div>
    <div class="modal-body text-info">
        <p ng-bind-html="msg | translate"></p>
        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-info" ng-style="getProgress()">
            </div>
        </div>
    </div>
</script>
<script type="text/ng-template" id="iframe.html">
    <div class="iframe-action">
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
        <button type="button" class="btn btn-success" ng-click="reload()" >{{ __('Reload') }}</button>
    </div>
    <iframe name="viewPage" ng-src="@{{url}}"></iframe>
</script>
<script type="text/ng-template" id="vnsTable.html">
    <div class="vns-table">
        <ul class="breadcrumb" ng-if="$breadcrumbs != undefined">
            <li ng-repeat="bre in $breadcrumbs">
                <a ng-if="!$last" href="javascript:void(0)" ng-click="params.breadcrumbClick($index)" ng-bind-html="bre.label"></a>
                <span ng-if="$last" ng-bind-html="bre.label"></span>
            </li>
        </ul>
        <table class="table table-striped table-hover" ui-tree="$treeOptions">
            <thead ng-class="{'no-sortable': $filters == undefined}">
            <tr>
                <th ng-repeat="(field, column) in $columns" ng-class="{'fixed-width': ['fixed','image','order','status','datetime','date','time'].indexOf(column.type) != -1,'sortable': column.sortable!=undefined&&column.order==undefined,'sort-asc': column.sortable=='asc','sort-desc': column.sortable=='desc'}" ng-click="sort(field)">
                    <span ng-bind-html="column.label"></span>
                </th>
                <th></th>
            </tr>
            <tr class="info" ng-if="$filters">
                <th ng-repeat="(field, column) in $columns" ng-switch="$filters[field].type" ng-class="{'fixed-width': ['fixed','image','order','status','datetime','date','time'].indexOf(column.type) != -1}">
                    <input ng-switch-when="text" type="text" ng-model="$filters[field].value" class="form-control input-sm">
                    <input ng-switch-when="number" type="text" ng-model="$filters[field].value" class="form-control input-sm">
                    <select ng-switch-when="status" class="form-control input-sm" ng-model="$filters[field].value">
                        <option value="">-</option>
                        <option value="1">{{__('Yes')}}</option>
                        <option value="0">{{__('No')}}</option>
                    </select>
                    <select ng-switch-when="select" class="form-control input-sm" ng-model="$filters[field].value">
                        <option value="">-</option>
                        <option ng-repeat="(label, value) in $filters[field].data" ng-value="value" ng-bind-html="label|translate"></option>
                    </select>
                    <i ng-switch-default class="fa fa-minus"></i>
                </th>
                <th><button class="btn btn-default btn-sm" ng-click="filter()"><i class="fa fa-search"></i> {{__('Search')}}</button></th>
            </tr>
            </thead>
            <tbody ui-tree-nodes ng-model="$rows" ng-if="$rows.length>0">
            <tr ui-tree-node ng-repeat="($order, row) in $rows">
                <td ng-repeat="(field, column) in $columns" data-title="@{{ column.label }}" ng-class="{'fixed-width': ['fixed','image','order','status','datetime','date','time'].indexOf(column.type) != -1, 'table-click': column.action, 'table-order': column.type=='order', 'angular-ui-tree-drag-show': column.dragShow}" ng-switch="column.type" ng-click="column.action(row)">
                    @{{ fieldValue = getValue(field, row, column); '' }}
                    <img ng-switch-when="image" ng-src="@{{ fieldValue }}" class="img-thumbnail" />
                    <span ng-switch-when="status">
                        <i ng-if="fieldValue == true" class="fa fa-check fa-lg text-success"></i>
                        <i ng-if="fieldValue == false" class="fa fa-times fa-lg text-danger"></i>
                    </span>
                    <div ng-switch-when="order" ui-tree-handle>
                        <i class="fa fa-arrows fa-fw"></i> <span>@{{$order+1}}</span>
                    </div>
                    <span ng-switch-when="datetime" class="@{{ column.text != undefined ?'text-'+column.text:'' }} @{{ column.size != undefined ?'size-'+column.size:'' }}"><i ng-if="column.icon" class="@{{ column.icon }}"></i>@{{ fieldValue|parseDate|date:'medium' }}</span>
                    <span ng-switch-when="date" class="@{{ column.text != undefined ?'text-'+column.text:'' }} @{{ column.size != undefined ?'size-'+column.size:'' }}"><i ng-if="column.icon" class="@{{ column.icon }}"></i>@{{ fieldValue|parseDate|date:'mediumDate' }}</span>
                    <span ng-switch-when="time" class="@{{ column.text != undefined ?'text-'+column.text:'' }} @{{ column.size != undefined ?'size-'+column.size:'' }}"><i ng-if="column.icon" class="@{{ column.icon }}"></i>@{{ fieldValue|parseDate|date:'mediumTime' }}</span>
                    <span ng-switch-default class="@{{ column.text != undefined ?'text-'+column.text:'' }} @{{ column.size != undefined ?'size-'+column.size:'' }}" ng-if="fieldValue"><i ng-if="column.icon" class="@{{ column.icon }}"></i><span ng-bind-html="fieldValue"></span></span>
                    <p ng-repeat="(and, col) in column.and" class="@{{ col.text != undefined ?'text-'+col.text:'' }} @{{ col.size != undefined ?'size-'+col.size:'' }}">
                        @{{ andValue = getValue(and, row, col); '' }}
                        <span ng-if="andValue"><i ng-if="col.icon" class="@{{ col.icon }}"></i><span ng-bind-html="andValue"></span></span>
                    </p>
                </td>
                <td class="fixed-action">
                    <div class="btn-group" uib-dropdown>
                        <a class="btn btn-default btn-sm" ng-click="$firstAction.callback(row)"><i ng-if="$firstAction.icon" ng-class="$firstAction.icon"></i> @{{ $firstAction.label }}</a>
                        <a ng-if="$actions" class="btn btn-default btn-sm dropdown-toggle" uib-dropdown-toggle><span class="caret"></span></a>
                        <ul ng-if="$actions" class="dropdown-menu dropdown-menu-right" uib-dropdown-menu>
                            <li ng-repeat="action in $actions" ng-class="{divider: action=='divider'}"><a ng-if="action!='divider'" href="javascript:void(0)" ng-click="action.callback(row)" ng-if="!action.hide(row)"><i ng-if="action.icon" ng-class="action.icon"></i> @{{ action.label }}</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div ng-if="$total > -1">
            <ul class="pagination pagination-sm" ng-if="$total>$count">
                <li ng-class="{disabled: params.page == 1}" ng-click="params.prevPage()"><a href="javascript:void(0)">&laquo;</a></li>
                <li class="pagination-select">
                    <select class="form-control input-sm" ng-model="params.page" ng-change="params.reload()">
                        <option ng-repeat="page in makePages()" ng-value="page">@{{ page }}</option>
                    </select>
                </li>
                <li ng-class="{disabled: params.page == params.totalPage}" ng-click="params.nextPage()"><a href="javascript:void(0)">&raquo;</a></li>
            </ul>
            <div class="btn-group pull-right">
                <a ng-repeat="count in $counts" href="javascript:void(0)" class="btn btn-default btn-sm" ng-class="{active: $count == count}" ng-click="params.setCount(count)">@{{ count }}</a>
            </div>
        </div>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia.html">
    <div class="vns-media-container">
        <div class="media-toolbars">
            <button class="btn btn-default" ng-click="uploadMedias()" ng-disabled="isLoading || defaultConfig.upload == false" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Upload') }}"><i class="fa fa-upload"></i></button>
            <button class="btn btn-default" ng-click="createFolder()" ng-disabled="isLoading || defaultConfig.createFolder == false" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Create folder') }}"><i class="fa fa-folder-open"></i></button>
            <button class="btn btn-primary" ng-click="selectMedias()" ng-disabled="selected == files || isLoading || defaultConfig.single" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Select all') }}"><i class="fa fa-check-square-o"></i></button>
            <button class="btn btn-warning" ng-click="unselectMedias()" ng-disabled="selected == 0 || isLoading || defaultConfig.single" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Unselect all') }}"><i class="fa fa-square-o"></i></button>
            <button class="btn btn-danger" ng-click="deleteMedias()" ng-disabled="selected == 0 || isLoading || defaultConfig.delete == false" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Deletes') }}"><i class="fa fa-trash-o"></i></button>
            <button class="btn btn-success" ng-click="loadMedias()" ng-disabled="isLoading" uib-tooltip-placement="bottom" uib-tooltip-trigger="mouseenter" uib-tooltip="{{ __('Refresh') }}"><i class="fa fa-refresh"></i></button>
        </div>
        <div class="vns-medias" ng-style="$root.modalMediaStyle">
            <div class="media-breadcrumb" ng-show="!isLoading">
                <ul class="breadcrumb">
                    <li><i class="fa fa-home" ng-if="breadcrumb.length == 0"></i><a ng-if="breadcrumb.length > 0" ng-click="clickBreadcrumb(-1)"><i class="fa fa-home"></i></a></li>
                    <li ng-repeat="(index, bre) in breadcrumb"><a ng-if="!$last" ng-click="clickBreadcrumb(index)">@{{bre.name}}</a><span ng-if="$last">@{{bre.name}}</span></li>
                </ul>
                <span class="info">(<span ng-bind="files"></span> {{ __('Files') }} - <span ng-bind="folders"></span> {{ __('Folders') }})</span>
            </div>
            <ul class="clearfix" ng-show="!isLoading">
                <li class="col-lg-0 col-md-2 col-sm-3 col-vns col-xs-6" ng-if="currentFolder != defaultConfig.folder" ng-click="clickBack()">
                    <div>
                        <div class="embed-responsive embed-responsive-4by3">
                            <vns-media-thumb-folder type="back"></vns-media-thumb-folder>
                        </div>
                        <div class="text">{{ __('Back') }}</div>
                    </div>
                </li>
                <li class="col-lg-0 col-md-2 col-sm-3 col-vns col-xs-6" ng-repeat="(index, media) in medias" ng-click="clickMedia(index, media.type)" ng-class="{selected: media.selected}" vns-media-context-menu ng-if="!media.deleted">
                    <div>
                        <div class="embed-responsive embed-responsive-4by3" ng-switch="media.type">
                            <img ng-switch-when="image" ng-src="@{{media.thumb}}" vns-media-thumb />
                            <vns-media-thumb-folder ng-switch-when="folder" type="@{{ (media.files == 0 && media.folders == 0) ? 'empty' : 'default' }}"></vns-media-thumb-folder>
                            <img class="file-thumb" ng-switch-default ng-src="@{{thumbs[media.type]}}" />
                        </div>
                        <div class="text">@{{media.name}}</div>
                    </div>
                    <div class="mask-selected" ng-show="media.selected"><span><i class="fa fa-check"></i></span></div>
                </li>
            </ul>
            <div class="media-loading" ng-show="isLoading">
                <i class="fa fa-spinner fa-pulse"></i> {{ __('Loading, please wait...') }}
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/input.html">
    <ul ng-if="defaultConfig.single" class="vns-media-input vns-media-input-single">
        <li ng-if="images != null && images != ''" ng-click="chosen()">
            <i class="fa fa-times" g-click="remove()"></i>
            <a><img ng-src="@{{thumb}}" vns-media-thumb/></a>
        </li>
        <li ng-if="images == null || images == ''" class="vns-media-input-btn" ng-click="chosen()">
            <span><i class="fa fa-camera"></i></span>
        </li>
    </ul>
    <ul ng-if="!defaultConfig.single" class="vns-media-input" as-sortable ng-model="images">
        <li ng-repeat="image in images" class="col-lg-0 col-md-2 col-sm-3 col-vns col-xs-6" data-as-sortable-item>
            <i class="fa fa-times" ng-click="remove(image)"></i>
            <a><img ng-src="@{{image.thumb}}" vns-media-thumb as-sortable-item-handle/></a>
        </li>
        <li class="col-lg-0 col-md-2 col-sm-3 col-vns col-xs-6 vns-media-input-btn" ng-click="chosen()">
            <span><i class="fa fa-camera"></i></span>
        </li>
    </ul>
</script>
<script type="text/ng-template" id="vnsMedia/modal.html">
    <div class="modal-header dialog-header-media">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title">
            <span class="fa fa-image"></span>
            <span >{{ __('Media') }}</span>
        </h4>
    </div>
    <div class="modal-body dialog-body-media" ng-style="modalBodyStyle">
        <vns-media ng-model="medias" media-config="config" />
    </div>
    <div class="modal-footer dialog-footer-media">
        <span class="pull-left">{{ __('Chose') }}: <span ng-bind="selectedMedias.length"></span></span>
        <button type="button" class="btn btn-default" ng-click="ok()" ng-disabled="selectedMedias.length == 0" >{{ __('Ok') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/modal/folder.html">
    <div class="modal-header dialog-header-notify">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title text-info">
            <span class="fa fa-exclamation-circle"></span> {{ __('Create folder') }}
        </h4>
    </div>
    <div class="modal-body text-info">
        <input type="text" class="form-control" ng-model="name" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="create()">{{ __('Create') }}</button>
        <button type="button" class="btn btn-default" ng-click="close()">{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/modal/rename.html">
    <div class="modal-header dialog-header-notify">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title text-info"><span
                    class="fa fa-exclamation-circle"></span> @{{ data.type == 'folder' ? 'Rename folder' : 'Rename file' | translate }} : @{{ data.name }}</h4>
    </div>
    <div class="modal-body text-info">
        <input type="text" class="form-control" ng-model="data.name"/>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="rename()">{{ __('Rename') }}</button>
        <button type="button" class="btn btn-default" ng-click="close()">{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/modal/upload.html">
    <div class="modal-header dialog-header-notify">
        <button type="button" class="close" ng-click="close()" ng-disabled="processing">&times;</button>
        <h4 class="modal-title text-info"><span class="fa fa-exclamation-circle"></span> {{ __('Upload') }}</h4>
    </div>
    <div class="modal-body dialog-body-upload">
        <form class="dropzone" current-folder="@{{currentFolder}}" on-success="success(media)" processing="processing" callback="callback">
            <div class="dz-message needsclick">
                {{ __('Drop files here or click to upload.') }}<br>
                <span class="note needsclick">{{ __('(The selected files will be automatically uploaded.)') }}</span>
            </div>
        </form>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/modal/url.html">
    <div class="modal-header dialog-header-notify">
        <button type="button" class="close" ng-click="close()">&times;</button>
        <h4 class="modal-title text-info"><span class="fa fa-exclamation-circle"></span> {{ __('Show URL') }}</h4>
    </div>
    <div class="modal-body text-info">
        <div class="input-group">
            <input type="text" class="form-control" ng-click="onTextClick($event)" ng-model="data.source"
                   ng-readonly="success!=false"/>
            <span class="input-group-btn" ng-switch="success">
                    <button ng-switch-when="true" style="width: 110px" class="btn btn-success"
                            type="button" disabled><i
                                class="fa fa-check"></i> {{ __('Copied') }}</button>
                    <button ng-switch-when="false" style="width: 110px"
                            class="btn btn-danger disabled" type="button"
                            tooltip-placement="top" tooltip-trigger="mouseenter"
                            tooltip="{{ __('Press Ctrl+C to copy') }}"><i
                                class="fa fa-check"></i> {{ __('Copy error') }}</button>
                    <button ng-switch-default style="width: 120px" class="btn btn-default"
                            type="button" clipboard text="data.source" on-copied="copied()"
                            on-error="error()"><i
                                class="fa fa-share-square-o"></i> {{ __('Copy') }}</button>
                </span>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="close()">{{ __('Ok') }}</button>
    </div>
</script>
<script type="text/ng-template" id="vnsMedia/modal/view.html">
    <h3 class="title">@{{data.name}} (@{{data.width}}x@{{data.height}})</h3>
    <button ng-click="close()" class="close pull-right" aria-hidden="true">&times;</button>
    <img ng-src="@{{data.source}}">
</script>
<script type="text/ng-template" id="media.html">
    <div class="container-fluid" ng-controller="MediaCtrl">
        <vns-media ng-model="medias" />
    </div>
</script>
<script type="text/ng-template" id="config.html">
    <div ng-controller="ConfigCtrl">
        <div class="container-fluid">
            <uib-tabset>
                <uib-tab ng-repeat="tab in tabs">
                    <uib-tab-heading>
                        <i class="@{{tab.icon}}"></i> @{{tab.title}}
                    </uib-tab-heading>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="35%">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr ng-repeat="(key, conf) in tab.data">
                            <td>@{{ conf.title | capitalize:true }} <button ng-if="conf.help != undefined" class="fa fa-question-circle help" uib-popover="@{{ conf.help }}" popover-placement="right" popover-trigger="focus"></button></td>
                            <td ng-switch="conf.type">
                                <textarea class="form-control" ng-switch-when="textarea" ng-model="conf.value"></textarea>
                                <input class="form-control" ng-switch-when="email" type="email" ng-model="conf.value">
                                <input class="form-control" ng-switch-when="number" type="number" ng-model="conf.value">
                                <div ng-switch-when="switch" class="btn-group">
                                    <label ng-repeat="data in conf.data" class="btn" ng-class="{'success': 'btn-success', 'primary': 'btn-primary', 'info': 'btn-info', 'warning': 'btn-warning', 'danger': 'btn-danger', undefined:'btn-default'}[data.type]" ng-model="conf.value" uib-btn-radio="data.value" uncheckable>@{{ data.text }}</label>
                                </div>
                                <textarea ng-switch-when="ckeditor" ui-tinymce="$root.tinymceOptions" ng-model="conf.value"></textarea>
                                <dropdown-select ng-switch-when="select" ng-model="conf.value" options="conf.data" empty-label="{{ __('None selected') }}"></dropdown-select>
                                <input-password ng-switch-when="password" ng-model="conf.value"></input-password>
                                <input class="form-control" ng-switch-default type="text" ng-model="conf.value">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </uib-tab>
            </uib-tabset>
        </div>
        <div class="content-btn">
            <button class="btn btn-primary" ng-click="updateConfig()"><i class="fa fa-floppy-o fa-fw"></i> {{ __('Save') }}</button>
        </div>
    </div>
</script>
<script type="text/ng-template" id="group.html">
    <div ng-controller="GroupCtrl">
        <div class="container-fluid">
            <div vns-table="tableParams"></div>
        </div>
        <div class="content-btn">
            <button ng-disabled="isLoading" class="btn btn-default" ng-click="tableParams.reload()"><i class="fa fa-refresh fa-fw"></i> {{ __('Reload') }}</button>
            <button class="btn btn-success" ng-click="new()"><i
                            class="fa fa-user-plus fa-fw"></i> {{ __('Add group') }}
            </button>
        </div>
    </div>

</script>
<script type="text/ng-template" id="group/edit.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Edit group') }}: @{{group.code}}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered" style="margin-bottom: 0">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td >{{ __('Code') }}</td>
                <td>
                    <input class="form-control" disabled="" type="text" ng-model="group.code">
                </td>
            </tr>
            <tr>
                <td >{{ __('Name') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="group.name">
                </td>
            </tr>
            <tr>
                <td >{{ __('Description') }}</td>
                <td>
                    <textarea class="form-control" ng-model="group.description"></textarea>
                </td>
            </tr>
            <tr>
                <td >{{ __('Permissions') }}</td>
                <td>
                    <tags-input ng-model="permissions" placeholder="{{ __('Add a permission') }}"
                                replace-spaces-with-dashes="false" add-from-autocomplete-only="true" display-property="name" key-property="code">
                        <auto-complete source="queryPermissions($query)" display-property="name"></auto-complete>
                    </tags-input>
                </td>
            </tr>
            <tr>
                <td >{{ __('Status') }}</td>
                <td>
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="group.status" uib-btn-radio="true">{{__('Enabled')}}</label>
                        <label class="btn btn-default" ng-model="group.status" uib-btn-radio="false">{{__('Disabled')}}</label>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="group/new.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Add group') }}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered" style="margin-bottom: 0">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td >{{ __('Code') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="group.code">
                </td>
            </tr>
            <tr>
                <td >{{ __('Name') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="group.name">
                </td>
            </tr>
            <tr>
                <td >{{ __('Description') }}</td>
                <td>
                    <textarea class="form-control" ng-model="group.description"></textarea>
                </td>
            </tr>
            <tr>
                <td >{{ __('Permissions') }}</td>
                <td>
                    <tags-input ng-model="permissions" placeholder="{{ __('Add a permission') }}"
                                replace-spaces-with-dashes="false" add-from-autocomplete-only="true" display-property="name" key-property="code">
                        <auto-complete source="queryPermissions($query)" display-property="name"></auto-complete>
                    </tags-input>
                </td>
            </tr>
            <tr>
                <td >{{ __('Status') }}</td>
                <td>
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="group.status" uib-btn-radio="true">{{__('Enabled')}}</label>
                        <label class="btn btn-default" ng-model="group.status" uib-btn-radio="false">{{__('Disabled')}}</label>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="user.html">
    <div ng-controller="UserCtrl">
        <div class="container-fluid">
            <div vns-table="tableParams"></div>
            <input type="file" id="avatar-input">
        </div>
        <div class="content-btn">
            <button ng-disabled="isLoading" class="btn btn-default" ng-click="tableParams.reload()"><i class="fa fa-refresh fa-fw"></i> {{ __('Reload') }}</button>
            <button class="btn btn-success" ng-click="new()"><i
                            class="fa fa-user-plus fa-fw"></i> {{ __('Add user') }}
            </button>
        </div>
    </div>
</script>
<script type="text/ng-template" id="user/view.html">
    <div class="modal-body text-center" ng-if="!user">
        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
    </div>
    <div class="modal-body" ng-if="user">
        <div class="media">
            <div class="media-left">
                <img class="media-object" ng-src="avatar/@{{user.id}}/50" alt="@{{ user.displayName }}">
            </div>
            <div class="media-body">
                <h4 class="media-heading">@{{ user.display_name }}</h4>
                <p>{{__('Group')}}: @{{user.group_name|translate}}</p>
                <p>{{__('Registered')}}: @{{user.created_at|parseDate|date:'medium'}}</p>
                <p ng-if="user.first_name || user.last_name">
                    <span>{{__('First name')}}: @{{user.first_name}}</span> | <span>{{__('Last name')}}: @{{user.last_name}}</span>
                </p>
                <p ng-if="user.email">{{__('Email')}}: @{{user.email}}</p>
                <p ng-if="user.phone">{{__('Phone')}}: @{{user.phone}}</p>
                <p ng-if="user.birthday">{{__('Birthday')}}: @{{user.birthday|parseDate|date:'shortDate'}}</p>
                <p ng-if="user.url">{{__('Homepage')}}: <a ng-href="@{{ user.url }}" target="_blank">@{{ user.url }}</a></p>
            </div>
        </div>
        <div ng-if="user.about" class="panel panel-info mt-10 mb-0">
            <div class="panel-body">
                @{{user.about}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="user/edit.html">
    <div class="modal-header">
        <h4 class="modal-title">{{__('Edit user')}}: @{{name}}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered" style="margin-bottom: 0">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td >{{ __('Email address') }}</td>
                <td>
                    <input class="form-control" type="email" ng-model="user.email" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Phone number') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="user.phone" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Display name') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="user.display_name" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Password') }}</td>
                <td>
                    <input-password ng-model="user.password"></input-password>
                    <span class="help-block" >{{ __('If you would like to change the password type a new one. Otherwise leave this blank.') }}</span>
                </td>
            </tr>
            <tr>
                <td >{{ __('Confirm password') }}</td>
                <td>
                    <input-password ng-model="user.password_confirmation"></input-password>
                </td>
            </tr>
            <tr>
                <td >{{ __('Send password?') }}</td>
                <td>
                    <div class="checkbox">
                        <label>
                            <input ng-model="password_send" type="checkbox" />
                            {{ __('Send this password to the user by email.') }}
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td >{{ __('Group') }}</td>
                <td>
                    <dropdown-select ng-model="user.group_code" options="groups" empty-label="{{__('Select group')}}"></dropdown-select>
                </td>
            </tr>
            <tr>
                <td >{{ __('Status') }}</td>
                <td>
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="user.status" uib-btn-radio="true">{{__('Enabled')}}</label>
                        <label class="btn btn-default" ng-model="user.status" uib-btn-radio="false">{{__('Disabled')}}</label>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="user/new.html">
    <div class="modal-header">
        <h4 class="modal-title">{{__('New user')}}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered" style="margin-bottom: 0">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td >{{ __('Email address') }}</td>
                <td>
                    <input class="form-control" type="email" ng-model="user.email" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Phone number') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="user.phone" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Display name') }}</td>
                <td>
                    <input class="form-control" type="text" ng-model="user.display_name" />
                </td>
            </tr>
            <tr>
                <td >{{ __('Password') }}</td>
                <td>
                    <input-password ng-model="user.password"></input-password>
                </td>
            </tr>
            <tr>
                <td >{{ __('Confirm password') }}</td>
                <td>
                    <input-password ng-model="user.password_confirmation"></input-password>
                </td>
            </tr>
            <tr>
                <td >{{ __('Send password?') }}</td>
                <td>
                    <div class="checkbox">
                        <label>
                            <input ng-model="password_send" type="checkbox" />
                            {{ __('Send this password to the user by email.') }}
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td >{{ __('Group') }}</td>
                <td>
                    <dropdown-select ng-model="user.group_code" options="groups" empty-label="{{ __('Select group') }}"></dropdown-select>
                </td>
            </tr>
            <tr>
                <td >{{ __('Status') }}</td>
                <td>
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="user.status" uib-btn-radio="true">{{ __('Enabled') }}</label>
                        <label class="btn btn-default" ng-model="user.status" uib-btn-radio="false">{{ __('Disabled') }}</label>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="module.html">
    <div ng-controller="ModuleCtrl">
        <div class="container-fluid">
            <div vns-table="tableParams"></div>
        </div>
        <div class="content-btn">
            <button ng-disabled="isLoading" class="btn btn-default" ng-click="tableParams.reload()"><i class="fa fa-refresh fa-fw"></i> {{ __('Reload') }}</button>
            <button class="btn btn-success" ng-click="new()"><i
                            class="fa fa-plus fa-fw"></i> {{ __('Install module') }}
            </button>
        </div>
    </div>
</script>
<script type="text/ng-template" id="module/new.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Install module from GitHub') }}</h4>
    </div>
    <div class="modal-body">
        <div ng-hide="stepInstall > 0">
            <div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-github"></i></span>
                    <input ng-model="github" type="text" class="form-control" placeholder="https://github.com/{organization}/{repository}" >
                </div>
                <span class="help-block">{{__('Enter the URL for a GitHub repository.')}}</span>
            </div>
            <div ng-if="error != null" uib-alert class="alert-danger" close="closeAlert()">@{{error}}</div>
            <div class="alert alert-dismissible" ng-class="{'alert-info': !info.installed, 'alert-danger': info.installed }" ng-if="info != null">
                <p><strong>@{{ info.name }} (@{{ info.displayName }})</strong></p>
                <p>{{__('Author')}}: @{{info.authors|parseObj}}</p>
                <p>{{__('Version')}}: @{{info.version}}</p>
                <p ng-show="info.installed">{{__('Installed')}}: @{{info.installed}}</p>
                <div class="panel panel-default mt-10 mb-0">
                    <div class="panel-body">
                        @{{info.description}}
                    </div>
                </div>
            </div>
        </div>
        <div ng-if="stepInstall > 0">
            <h4>{{ __('Installing module') }}: @{{ info.displayName }} @{{ info.version }}</h4>
            <p ng-if="stepInstall > 0">{{ __('Downloading install package from GitHub...') }}</p>
            <p ng-if="stepInstall > 1">{{ __('Unpacking the package...') }}</p>
            <p ng-if="stepInstall > 2">{{ __('Installing the module...') }}</p>
            <p ng-if="stepInstall == 4" class="text-success">{{ __('Successfully installed the module.') }}</p>
            <p ng-if="stepInstall == 5" class="text-danger">{{ __('Module install failed.') }}</p>
        </div>
    </div>
    <div class="modal-footer">
        <button ng-if="stepInstall == 4" class="btn btn-success" ng-click="activate()"><i class="fa fa-check fa-fw"></i> {{ __('Activate module') }}</button>
        <button ng-if="info != null && info.installed == undefined && stepInstall == 0" class="btn btn-success" ng-click="install()"><i class="fa fa-upload fa-fw"></i> {{ __('Install') }}</button>
        <button ng-if="info.update && stepInstall == 0" class="btn btn-warning" ng-click="install()"><i class="fa fa-refresh fa-fw"></i> {{ __('Update') }}</button>
        <button ng-if="stepInstall == 0" ng-disabled="isInstalling||isChecking" class="btn btn-default" ng-click="check()"><i class="fa fa-info fa-fw"></i> {{ __('Check') }}</button>
        <button type="button" ng-disabled="isInstalling||isChecking" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="module/view.html">
    <div class="modal-body">
        <div class="media">
            <div class="media-left">
                <img class="media-object" ng-src="@{{ module.image }}" alt="@{{ module.displayName }}">
            </div>
            <div class="media-body">
                <h4 class="media-heading">@{{ module.name }} (@{{ module.displayName }})</h4>
                <p>{{__('Author')}}: @{{ module.authors|parseObj }}</p>
                <p>{{__('Version')}}: @{{ module.version }}</p>
                <p>{{__('License')}}: @{{ module.license }}</p>
                <p>{{__('Homepage')}}: <a ng-href="@{{ module.homepage }}" target="_blank">@{{ module.homepage }}</a></p>
            </div>
        </div>
        <div class="panel panel-info mt-10 mb-0">
            <div class="panel-body">
                @{{module.description}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="theme.html">
    <div ng-controller="ThemeCtrl">
        <div class="container-fluid">
            <div vns-table="tableParams"></div>
        </div>
        <div class="content-btn">
            <button ng-disabled="isLoading" class="btn btn-default" ng-click="tableParams.reload()"><i class="fa fa-refresh fa-fw"></i> {{ __('Reload') }}</button>
            <button class="btn btn-primary" ng-click="gadget()"><i
                            class="fa fa-object-group fa-fw"></i> {{ __('Gadgets manage') }}
            </button>
            <button class="btn btn-success" ng-click="new()"><i
                            class="fa fa-plus fa-fw"></i> {{ __('Install theme') }}
            </button>
        </div>
    </div>
</script>
<script type="text/ng-template" id="theme/gadget.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Gadget area in theme') }}: @{{ $name }}</h4>
    </div>
    <div class="modal-body">
        <uib-accordion>
            <div ng-repeat="(groupKey,groupLabel) in $groups" uib-accordion-group class="panel-default">
                <uib-accordion-heading>
                    @{{ groupLabel|translate }} <span class="badge">@{{ $themeGadgets[groupKey].length }}</span>
                </uib-accordion-heading>
                <div ui-tree>
                    <ul class="list-gadget" ui-tree-nodes="" ng-model="$themeGadgets[groupKey]">
                        <li ng-repeat="(key, gadget) in $themeGadgets[groupKey]" ng-class="{'gadget-not': $gadgets[gadget.name]==undefined}" ui-tree-node>
                            <i class="fa fa-list-ul" ui-tree-handle></i>
                            <span ng-if="$gadgets[gadget.name]!=undefined">@{{ $gadgets[gadget.name].name|translate }}</span>
                            <span ng-if="$gadgets[gadget.name]==undefined">@{{ gadget.name }} - <i>{{__('not installed')}}</i></span>
                            <span class="gadget-action">
                            <i class="fa fa-fw" ng-class="gadget.visible?'fa-eye':'fa-eye-slash'" ng-click="toggleVisible(gadget)"></i>
                            <i class="fa fa-pencil fa-fw" ng-if="$gadgets[gadget.name].parameters" ng-click="edit(gadget)"></i>
                            <i class="fa fa-trash-o fa-fw" ng-click="delete(groupKey,key)"></i>
                        </span>
                        </li>
                    </ul>
                </div>
                <div class="add-gadget" ng-click="add(groupKey)"><i class="fa fa-plus fa-fw"></i>{{__('Add gadget')}}</div>
            </div>
        </uib-accordion>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="theme/gadget/edit.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Configure for') }}: @{{ $name|translate }}</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label">{{__('Visible')}}</label>
                <div class="col-sm-9">
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="$gadget.visible" uib-btn-radio="true" uncheckable><i class="fa fa-eye fa-fw"></i></label>
                        <label class="btn btn-default" ng-model="$gadget.visible" uib-btn-radio="false" uncheckable><i class="fa fa-eye-slash fa-fw"></i></label>
                    </div>
                </div>
            </div>
            <div class="form-group" ng-repeat="(key, param) in $parameters">
                <label class="col-sm-3 control-label">@{{ param.label|translate }}</label>
                <div class="col-sm-9" ng-switch="param.type">
                    <textarea class="form-control" ng-switch-when="textarea" ng-model="$gadget.parameters[key]"></textarea>
                    <input class="form-control" ng-switch-when="email" type="email" ng-model="$gadget.parameters[key]">
                    <input class="form-control" ng-switch-when="number" type="number" ng-model="$gadget.parameters[key]">
                    <div ng-switch-when="switch" class="btn-group">
                        <label ng-repeat="data in param.data" class="btn" ng-class="{'success': 'btn-success', 'primary': 'btn-primary', 'info': 'btn-info', 'warning': 'btn-warning', 'danger': 'btn-danger', undefined:'btn-default'}[data.type]" ng-model="$gadget.parameters[key]" uib-btn-radio="data.value" uncheckable>@{{ data.text }}</label>
                    </div>
                    <textarea ng-switch-when="ckeditor" ui-tinymce="$root.tinymceOptions" ng-model="$gadget.parameters[key]"></textarea>
                    <dropdown-select ng-switch-when="select" ng-model="$gadget.parameters[key]" options="param.data" empty-label="{{ __('None selected') }}"></dropdown-select>
                    <input-password ng-switch-when="password" ng-model="$gadget.parameters[key]"></input-password>
                    <input class="form-control" ng-switch-default type="text" ng-model="$gadget.parameters[key]">
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" >{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="theme/gadget/add.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Add gadget') }}<span ng-if="$gadget.name">: @{{ $gadgets[$gadget.name].name }}</span></h4>
    </div>
    <div class="modal-body">
        <div ng-if="$gadget.name == undefined">
            <div class="media media-gadget" ng-repeat="(key,gadget) in $gadgets" ng-click="init(key)">
                <div class="media-left">
                    <img ng-if="gadget.image" class="media-object" ng-src="@{{gadget.image}}">
                    <img ng-if="gadget.image == undefined" class="media-object" ng-src="@{{$root.Thumbs.noImage}}">
                </div>
                <div class="media-body">
                    <h4 class="media-heading text-info">@{{ gadget.name }} <small class="size-12">v@{{ gadget.module.version }} - {{__('by')}} @{{ gadget.module.authors|parseObj }}</small></h4>
                    <p class="text-muted">@{{ gadget.description }}</p>
                </div>
            </div>
        </div>
        <form class="form-horizontal" ng-if="$gadget.name != undefined">
            <div class="form-group">
                <label class="col-sm-3 control-label">{{__('Visible')}}</label>
                <div class="col-sm-9">
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="$gadget.visible" uib-btn-radio="true" uncheckable><i class="fa fa-eye fa-fw"></i></label>
                        <label class="btn btn-default" ng-model="$gadget.visible" uib-btn-radio="false" uncheckable><i class="fa fa-eye-slash fa-fw"></i></label>
                    </div>
                </div>
            </div>
            <div class="form-group" ng-repeat="(key, param) in $parameters">
                <label class="col-sm-3 control-label">@{{ param.label|translate }}</label>
                <div class="col-sm-9" ng-switch="param.type">
                    <textarea class="form-control" ng-switch-when="textarea" ng-model="$gadget.parameters[key]"></textarea>
                    <input class="form-control" ng-switch-when="email" type="email" ng-model="$gadget.parameters[key]">
                    <input class="form-control" ng-switch-when="number" type="number" ng-model="$gadget.parameters[key]">
                    <div ng-switch-when="switch" class="btn-group">
                        <label ng-repeat="data in param.data" class="btn" ng-class="{'success': 'btn-success', 'primary': 'btn-primary', 'info': 'btn-info', 'warning': 'btn-warning', 'danger': 'btn-danger', undefined:'btn-default'}[data.type]" ng-model="$gadget.parameters[key]" uib-btn-radio="data.value" uncheckable>@{{ data.text }}</label>
                    </div>
                    <textarea ng-switch-when="ckeditor" ui-tinymce="$root.tinymceOptions" ng-model="$gadget.parameters[key]"></textarea>
                    <dropdown-select ng-switch-when="select" ng-model="$gadget.parameters[key]" options="param.data" empty-label="{{ __('None selected') }}"></dropdown-select>
                    <input-password ng-switch-when="password" ng-model="$gadget.parameters[key]"></input-password>
                    <input class="form-control" ng-switch-default type="text" ng-model="$gadget.parameters[key]">
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="save()" ng-if="$gadget.name">{{ __('Save') }}</button>
        <button type="button" class="btn btn-primary" ng-click="close()">{{ __('Close') }}</button>
    </div>
</script>
<script type="text/ng-template" id="theme/new.html">
    <div class="modal-header">
        <h4 class="modal-title">{{ __('Install theme from GitHub') }}</h4>
    </div>
    <div class="modal-body">
        <div ng-hide="stepInstall > 0">
            <div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-github"></i></span>
                    <input ng-model="github" type="text" class="form-control" placeholder="https://github.com/{organization}/{repository}" >
                </div>
                <span class="help-block">{{__('Enter the URL for a GitHub repository.')}}</span>
            </div>
            <div ng-if="error != null" uib-alert class="alert-danger" close="closeAlert()">@{{error}}</div>
            <div class="alert alert-dismissible" ng-class="{'alert-info': !info.installed, 'alert-danger': info.installed }" ng-if="info != null">
                <p><strong>@{{ info.name }} (@{{ info.displayName }})</strong></p>
                <p>{{__('Author')}}: @{{info.authors|parseObj}}</p>
                <p>{{__('Version')}}: @{{info.version}}</p>
                <p ng-show="info.installed">{{__('Installed')}}: @{{info.installed}}</p>
                <div class="panel panel-default mt-10 mb-0">
                    <div class="panel-body">
                        @{{info.description}}
                    </div>
                </div>
            </div>
        </div>
        <div ng-if="stepInstall > 0">
            <h4>{{ __('Installing theme') }}: @{{ info.displayName }} @{{ info.version }}</h4>
            <p ng-if="stepInstall > 0">{{ __('Downloading install package from GitHub...') }}</p>
            <p ng-if="stepInstall > 1">{{ __('Unpacking the package...') }}</p>
            <p ng-if="stepInstall > 2">{{ __('Installing the theme...') }}</p>
            <p ng-if="stepInstall == 4" class="text-success">{{ __('Successfully installed the theme.') }}</p>
            <p ng-if="stepInstall == 5" class="text-danger">{{ __('Theme install failed.') }}</p>
        </div>
    </div>
    <div class="modal-footer">
        <button ng-if="stepInstall == 4" class="btn btn-success" ng-click="default()"><i class="fa fa-check fa-fw"></i> {{ __('Set default') }}</button>
        <button ng-if="info != null && info.installed == undefined && stepInstall == 0" class="btn btn-success" ng-click="install()"><i class="fa fa-upload fa-fw"></i> {{ __('Install') }}</button>
        <button ng-if="info.update && stepInstall == 0" class="btn btn-warning" ng-click="install()"><i class="fa fa-refresh fa-fw"></i> {{ __('Update') }}</button>
        <button ng-if="stepInstall == 0" ng-disabled="isInstalling||isChecking" class="btn btn-default" ng-click="check()"><i class="fa fa-info fa-fw"></i> {{ __('Check') }}</button>
        <button type="button" ng-disabled="isInstalling||isChecking" class="btn btn-primary" ng-click="close()" >{{ __('Close') }}</button>
    </div>
</script>
