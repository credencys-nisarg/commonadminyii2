$(document).ready(function() {    
    //$(".all-chk").click(function(){ 
    $(".all-chk").on("click", function(){
        var chkgrp = $(this).attr('id');
        if ($(this).is(':checked')) {
            $('.sub_'+chkgrp).prop('checked',true);
        } else {
            $('.sub_'+chkgrp).prop('checked',false);
        }       
    });
    $(".sub-chk").on("click", function(){
        var chk_classes = $(this).attr('class');
        var explode_chk_class = chk_classes.split(" ")[1];
        var main_chk_id = explode_chk_class.split("_")[1];
        var chkall = $('.'+explode_chk_class).length;
        var checkedall = $('input[type=checkbox].'+explode_chk_class+':checked').length;
        if(chkall == checkedall){
            $('#'+main_chk_id).prop('checked',true);
        }else{
            $('#'+main_chk_id).prop('checked',false);
        }
    });
});