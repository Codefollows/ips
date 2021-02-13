;(function($,_,undefined){"use strict";ips.controller.register('core.admin.settings.advanced',{defaultStatus:false,initialize:function(){this.defaultStatus=$('#check_lazy_load_enabled').is(':checked');this.on('change','#check_lazy_load_enabled',this.promptRebuildPreference);},promptRebuildPreference:function(e){if(this.defaultStatus==$('#check_lazy_load_enabled').is(':checked')){$('input[name=rebuildPosts]').val(0);return;}
ips.ui.alert.show({type:'confirm',message:ips.getString('imageProxyRebuild'),subText:$('#check_lazy_load_enabled').is(':checked')?ips.getString('imageLazyLoadRebuildBlurbEnable'):ips.getString('imageLazyLoadRebuildBlurbDisable'),icon:'question',buttons:{ok:ips.getString('imageProxyRebuildYes'),cancel:ips.getString('imageProxyRebuildNo')},callbacks:{ok:function(){$('input[name=rebuildPosts]').val(1);},cancel:function(){$('input[name=rebuildPosts]').val(0);}}});}});}(jQuery,_));;
;(function($,_,undefined){"use strict";ips.controller.register('core.admin.settings.posting',{defaultStatus:false,initialize:function(){this.defaultStatus=$('input[name=remote_image_proxy]:checked').val();this.on('change','input[name=remote_image_proxy]',this.promptRebuildPreference);},promptRebuildPreference:function(e){var currentValue=$('input[name=remote_image_proxy]:checked').val();if(this.defaultStatus==currentValue||(this.defaultStatus>0&&currentValue>0)){$('input[name=rebuildImageProxy]').val(0);return;}
ips.ui.alert.show({type:'confirm',message:ips.getString('imageProxyRebuild'),subText:$('#check_remote_image_proxy').is(':checked')?ips.getString('imageProxyRebuildBlurbEnable'):ips.getString('imageProxyRebuildBlurbDisable'),icon:'question',buttons:{ok:ips.getString('imageProxyRebuildYes'),cancel:ips.getString('imageProxyRebuildNo')},callbacks:{ok:function(){$('input[name=rebuildImageProxy]').val(1);},cancel:function(){$('input[name=rebuildImageProxy]').val(0);}}});}});}(jQuery,_));;