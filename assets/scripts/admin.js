(()=>{var t={108:()=>{var t;t=jQuery,void 0!==BkbmRkburAdminData.installation&&1!=BkbmRkburAdminData.installation&&t.when(t.ajax({type:"POST",url:ajaxurl,data:{action:"bkbm_rkbur_installation_counter",product_id:BkbmRkburAdminData.product_id},dataType:"JSON"})).done((function(t){}))},998:()=>{!function(t){if(t("#bkb_rkb_status").length&&t("#container-bkb_rkb_user_roles").length){var e=t("#bkb_rkb_status"),a=t("#container-bkb_rkb_user_roles");1==e.val()?a.show("slow"):a.hide("slow"),e.on("change",(function(){1==t(this).val()?a.show("slow"):a.hide("slow")}))}if("undefined"==typeof inlineEditPost)return"";var n=inlineEditPost.edit;inlineEditPost.edit=function(e){n.apply(this,arguments);var a=0;if("object"==typeof e&&(a=parseInt(this.getId(e))),a>0){var i=t("#edit-"+a),r=t("#bkb_rkb_status-"+a).data("status_code");i.find('select[name="bkb_rkb_status"]').val(1==r?1:0)}},t("#bulk_edit").on("click",(function(){let e=t("#bulk-edit");$post_ids=new Array,e.find("#bulk-titles-list .button-link.ntdelbutton").each((function(){$post_ids.push(t(this).attr("id").replace(/_/g,""))}));var a=e.find('select[name="bkb_rkb_status"]').val();t.ajax({url:ajaxurl,type:"POST",async:!1,cache:!1,data:{action:"manage_wp_posts_using_bulk_edit_rkb",post_ids:$post_ids,bkb_rkb_status:a}})}))}(jQuery)}},e={};function a(n){var i=e[n];if(void 0!==i)return i.exports;var r=e[n]={exports:{}};return t[n](r,r.exports,a),r.exports}a.n=t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return a.d(e,{a:e}),e},a.d=(t,e)=>{for(var n in e)a.o(e,n)&&!a.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},a.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),(()=>{"use strict";a(108),a(998)})()})();