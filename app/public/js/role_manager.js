"use strict";

(function ($) {
    var roleManagementChanging=true;

    $(document).on('change','input.route-item', function(){
        var $this                   = $(this);
        if(roleManagementChanging==true){
            roleManagementChanging = false;
            var mainGroupItems          = $(this).closest('.route-group').find('.route-item').length;
            var mainGroupItemsChecked   = $(this).closest('.route-group').find('.route-item:checked').length;
            var subGroupItems           = $(this).closest('.route-subgroup').find('.route-item').length;
            var subGroupItemsChecked    = $(this).closest('.route-subgroup').find('.route-item:checked').length;
            if(mainGroupItems==mainGroupItemsChecked){
                $(this).closest('.route-group').find('.module').prop('checked',true);
            }
            else{
                $(this).closest('.route-group').find('.module').prop('checked', false);
            }
            if(subGroupItems==subGroupItemsChecked){
                $(this).closest('.route-subgroup').find('.sub-module').prop('checked',true);
            }
            else{
                $(this).closest('.route-subgroup').find('.sub-module').prop('checked', false);
            }
            roleManagementChanging = true;
        }
    });
    $(document).on('change','input.sub-module', function(){
        var $this                   = $(this);
        if(roleManagementChanging==true){
            roleManagementChanging = false;
            if($this.prop('checked')==true){
                $this.closest('.route-subgroup').find('input.route-item').prop('checked',true);
            }
            else{
                $this.closest('.route-subgroup').find('input.route-item').prop('checked', false);
            }
            var mainGroupItems          = $(this).closest('.route-group').find('.route-item').length;
            var mainGroupItemsChecked   = $(this).closest('.route-group').find('.route-item:checked').length;
            if(mainGroupItems==mainGroupItemsChecked){
                $(this).closest('.route-group').find('.module').prop('checked',true);
            }
            else{
                $(this).closest('.route-group').find('.module').prop('checked', false);
            }
            roleManagementChanging = true;
        }
    });
    $(document).on('change','input.module', function(){
        if(roleManagementChanging==true){
            roleManagementChanging = false;
            var $this                   = $(this);
            if($this.prop('checked')==true){
                $this.closest('.route-group').find('input.sub-module').prop('checked',true);
                $this.closest('.route-group').find('input.route-item').prop('checked',true);
            }
            else{
                $this.closest('.route-group').find('input.sub-module').prop('checked', false);
                $this.closest('.route-group').find('input.route-item').prop('checked', false);
            }
            roleManagementChanging = true;
        }
    })
})(jQuery);
