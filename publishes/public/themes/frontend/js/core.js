/**
 * Created by VnS on 3/31/2016.
 */
//var socket = io('http://127.0.0.1:8088');

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

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
