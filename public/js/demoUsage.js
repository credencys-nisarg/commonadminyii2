//selector can be class,Id or additional attributes
var acceptedFileExtComponent = '.jpeg,.jpg,.png,.gif';
var imagePathComponent = upload_url + "assets/component/";
var imageThumbPathComponent = upload_url + "assets/component/thumb/";
var imagePathReplacement = upload_url + "assets/replacement_part/";
var imageThumbPathReplacement = upload_url + "assets/replacement_part/thumb/";
$(document).ready(function () {
    /**
     * @desc for setting multiselect
     */
    app.config.setMultiSelect('.multiselect');

    /**
     * @desc for setting From and To date with validation grater than current date
     */
    app.config.setFromAndToDatePiker('#fromDate','#toDate');    // For Date Renges
    app.config.setDatePiker('#fromDate');   // For single Date

    /**
     * @desc usage of eventhendler and sweetAlert confirm with message
     */
    app.config.eventHandler('#deleteAssetType', 'click', function (){
        sweet.confirm.title(message.module.dropzone.deleteComman.deleteMsg.msg).show(function(){
            //app.module.deleteModelRecord(url,'searchPartSubType',message.module.partSubType.deleteMsg.msg);
        });
    });

    /**
     * @desc usage of ecoAjaxRequest
     * @returns {boolean}
     */
    function saveFormByAjax() {
        var id = $("#primary_id").val();
        var url = $(this).attr('action');
        var formObj = this;
        app.config.ecoAjaxRequest(
            {
                type: "post",
                url: siteUrl + url,
                data: new FormData(formObj),
                cache: false,
                contentType: false,
                dataType: 'json',
                processData: false,
            },
            function (data) {
                console.log(data);
            },
            function (err) {
                console.log(err);
            }
        );
        return false;
    }

    /**
     * usage of Dropzone
     */
    setTimeout(function () {
        bindDropzone('add');
    }, 2200);

    /**
     * @desc For binding dropzone to form
     * @param mode form opening mode
     */
    function bindDropzone(mode) {
        if ($("#dZUpload1").length) {
            url = siteUrl + 'componentMaster/create/' + $('#component_id').val();
            primaryKeyVal = $('#component_id').val();
            getImageUrl = 'componentMaster/getImages/';
            deleteImageUrl = 'componentMaster/DeleteImages/';
            app.module.setPopUpDropzone(primaryKeyVal, url, getImageUrl, deleteImageUrl, '#dZUpload1', '#component-master-form', '#old_images', 2, acceptedFileExtComponent, imagePathComponent, imageThumbPathComponent, 'component-part-master-grid', 'Component saved successfully.',mode);
        }
    }

    /**
     * @desc usage of tinymce
     */
    app.config.tinymceInit('[data-class="tinyMceEditor"]');
});