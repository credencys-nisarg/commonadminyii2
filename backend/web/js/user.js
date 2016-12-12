/**
 * Created by rajesh-ubnt on 13/10/16.
 * @desc those function will set all cropping functionality
 * @Ref-1 bind image in popup
 * @Ref-2 set cropBox
 * @Ref-3 works when dragging Crop Box
 */
var profileImage;
var profileImagePath;
// Ref-1
function applyCropping(file) {
    notify.pnotify.body("Applying Cropping...").type('danger').show();
    $('#myCropModal').modal('show');
    profileImage =file.name;
    imgUrl = profileImagePath+profileImage;
    $('#user-user_image').attr('src',imgUrl);
    $('#imageName').val(profileImage);
    $('#imageUrl').val($('#user-user_image').attr('src'));
    $('.jcrop-holder').find('img').attr('src',imgUrl);
    setTimeout(function(){
        callTargetJcrop();
        $('#cropButton').show();
    }, 1000);
}
// Ref-2
function callTargetJcrop()
{
    var width  = $('#user-user_image').prop('naturalWidth');
    var height = $('#user-user_image').prop('naturalHeight');
    $('#user-user_image').Jcrop({
        // aspectRatio: 5 / 3,
        trueSize:[width,height],
        onSelect: updateCoords,
        setSelect: [ 0, 0, 400, 400 ]
    });
}
// Ref-3
function updateCoords(c)
{
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#image_width').val(c.w);
    $('#h').val(c.h);
    $('#image_height').val(c.h);
};
$(document).ready(function () {

    //$(".fancybox").fancybox();
     $('.fancybox').fancybox({
      beforeShow : function(){
       this.title =  $(this.element).data("caption");
      }
     });


    app.config.eventHandler('#crop_form', 'submit', function (e) {
        var frm = $(this); //just sent text
        app.config.ajaxRequest(
            {
                type: 'POST',
                url: frm.attr('action'),
                dataType: 'json',
                data: frm.serialize(),
            },
            function (data) {
               notify.pnotify.body("Cropping Successful.").show();
                $('#myCropModal').modal('hide');
            },
            function (err) {
                notify.pnotify.body("Cropping Successful.").show();
                $('#myCropModal').modal('hide');
            }
        );
        return false;
    });
});