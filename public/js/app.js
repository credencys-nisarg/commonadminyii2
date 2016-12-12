var app = {
    config: {
        siteUrl: "",
        uploadUrl: "",
        /*
         * function to check value in array
         * @array : is the array 
         * @value : check this value exists in array or not
         */
        isInArray: function (value, array) {
            return array.indexOf(value) > -1;
        },
        /*
         * @objectStr : on which process occur
         * @event : Which action function will trigger for ex change,click etc
         * @handler : method which will execute after trigger event
         */
        eventHandler: function (objectStr, event, handler) {
            if ((objectStr instanceof $)) {
                objectStr = objectStr.selector;
            }
            $(document).on(event, objectStr, handler);
        },
        /*
         * desable event on individual class or id
         */
        removeEventHandler: function (object, event) {
            $(document).undelegate(object, event);
        },
        /*
         * 
         * @param {type} url : url on which we need to make ajax call
         * @param {type} type : data Type(json or xml)
         * @param {type} data : data what we want to send to action
         * @returns {jqXHR} whole response of ajax
         */
        ajaxRequest: function (requestArray, successHandler, errorHandler) {
            return (
                        $.ajax({
                            url: requestArray.url,
                            type: requestArray.type,
                            data: requestArray.data,
                            dataType: requestArray.dataType,
                            contentType: requestArray.contentType,
                            cache: requestArray.cache,
                            processData: requestArray.processData,
                            success: successHandler,
                            error: errorHandler
                        })
                    );
        },
        /*
         * This function is used to set multiselect dropdown
         */
        setMultiSelect: function (selector) {
            $(selector).multiselect({
                //  includeSelectAllOption: true,
                enableCaseInsensitiveFiltering: true,
                nonSelectedText: '',
                numberDisplayed: 2,
                nSelectedText: 'selected',
                enableClickableOptGroups: false,
                buttonText: function (options, select) {
                    if (options.length == 0) {
                        return $(select).attr('title');
                    } else {
                        var selected = 0;
                        options.each(function () {
                            selected += 1;
                        });
                        var arr = [];
                        var str = $(select).val();
                        arr.push.apply(arr, str.toString().split(",").map(String));
                        var str = '';
                        $.each(arr, function (i, selected) {
                            str = str + $(select).find('option[value="' + selected + '"]').html() + ',';
                        });
                        str = str.slice(0, -1);
                        return $(select).attr('data-title') + str + '<b class="caret"></b>';
                    }
                },
            });
        },
        /*
         * @param {type} tinySelector : selector on which you want to intialize TinyMCE
         */
        tinymceInit: function (tinySelector) {
            tinymce.remove();
            tinymce.init({
                selector: tinySelector,
                menubar: false
            });
        },
        /*
         * @param {type} form : form selector like id,class
         */
        clearForm: function (form) {
            // iterate over all of the inputs for the form
            // element that was passed in
            $('select').select2('val', '');
            $(':input', form).each(function () {
                var type = this.type;
                var tag = this.tagName.toLowerCase(); // normalize case
                // it's ok to reset the value attr of text inputs,
                // password inputs, and textareas
                if (type == 'text' || type == 'password' || tag == 'textarea')
                    this.value = "";
                // checkboxes and radios need to have their checked state cleared
                // but should *not* have their 'value' changed
                else if (type == 'checkbox' || type == 'radio')
                    this.checked = false;
                // select elements need to have their 'selectedIndex' property set to -1
                // (this works for both single and multiple select elements)
                else if (tag == 'select')
                    this.selectedIndex = -1;
            });
        },
        /*
         * It will set DatePiker for from and to date
         */
        setFromAndToDatePiker: function (fromDate,toDate) {
            var startDate = new Date('01/01/1900');
            var FromEndDate = new Date();
            var ToEndDate = new Date();

            $(fromDate).datepicker({
                weekStart: 1,
                startDate: '01/01/1900',
                endDate: FromEndDate,
                autoclose: true,
                format: "yyyy-mm-dd"
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $(toDate).val('');
                $(toDate).datepicker();
                $(toDate).datepicker('setStartDate', startDate);
            });
            $(toDate).datepicker({
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                format: "yyyy-mm-dd"
            }).on('changeDate', function (selected) {
                FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
            });

        },
        setDatePiker: function (selector) {
            var startDate = new Date('01/01/1900');
            var ToEndDate = new Date();
            $(selector).datepicker({
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                format: "yyyy-mm-dd"
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            });
        },
        /*
         * It will load Date Picker
         */
        datepicker: function (selector) {
            var d = new Date();
            var year = d.getFullYear();
            $(selector).datepicker({
                changeYear: true,
                yearRange: "2000:" + year,
                format: "yyyy-mm-dd",
                autoclose: true,
            });
        },
        /*
         * It will load Time Picker
         */
        timepicker: function (selector,timeInterval) {
            var times = new Date();
            $(selector).timepicker({
                interval: timeInterval
            });
        },
        isObjectExist: function (obj) {
            return obj instanceof Object && !($.isEmptyObject(obj));
        }
    },
    module: {
        /**
         * @desc to check passed string is object or not if string then convert to object
         * @param objectStr
         * @returns {*}
         */
        checkObjectAndApplyEval: function (objectStr) {
            if (typeof objectStr == 'string' || objectStr instanceof String) {
                objectStr = eval(objectStr);
            }
            return objectStr;
        },
        /**
         * Desc For adding dropzone in popup
         * @param primaryKeyVal
         * @param url
         * @param getImageUrl
         * @param deleteImageUrl
         * @param dropzoneSelector
         * @param formSelector
         * @param maxFileLimit
         * @param acceptedFileExt
         * @param imagePath
         * @param imageThumbPath
         * @param gridIDForRefresh
         */
        setPopUpDropzone: function (primaryKeyVal, url, getImageUrl, deleteImageUrl, dropzoneSelector, formSelector, oldImageSelector, maxFileLimit, acceptedFileExt, imagePath, imageThumbPath, gridIDForRefresh, successMsg, mode) {
            var files = [];
            var updateForm = 0;
            var showRemoveLink = true;
            var tempImgArray = [];
            Dropzone.autoDiscover = false;
            if (mode == 'view')
                showRemoveLink = false;
            myDropzonepopup = new Dropzone(dropzoneSelector, {
                url: url,
                acceptedFiles: acceptedFileExt,
                addRemoveLinks: showRemoveLink,
                dictDefaultMessage: "<i class='fa fa-cloud-download'></i> Drop image here to upload, <span class='color-primary'> <div id='clickable' > or browse </div></span> ",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: maxFileLimit,
                dictRemoveFileConfirmation: "Are you sure you want to delete this file ?",
                dictInvalidFileType: "You can only upload images.",
                dictMaxFilesExceeded: "You have reached maximum limit",
                init: function () {
                    if (primaryKeyVal != '') {
                        $.get(siteUrl + getImageUrl + primaryKeyVal, function (data) {
                            var image_data = $.parseJSON(data);
                            if (image_data != null) {
                                $.each(image_data, function (key, value) {
                                    var mockFile = {name: value[0], id: value[1], size: value[2]};
                                    tempImgArray.push(value[0]);
                                    myDropzonepopup.options.addedfile.call(myDropzonepopup, mockFile);
                                    myDropzonepopup.files.push(mockFile);
                                    var extension = ((value[0]).substring((value[0]).lastIndexOf(".") + 1));
                                    if ($.inArray(extension, ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"]) == "-1") {
                                        myDropzonepopup.emit("thumbnail", mockFile, imageThumbPath + value[0]);
                                        myDropzonepopup.options.thumbnail.call(myDropzonepopup, mockFile, siteUrl + "images/file_icon.png");
                                    } else {
                                        var imagepath_url = imageThumbPath + value[0];
                                        $.ajax({
                                            url: imagepath_url,
                                            success: function (data) {
                                                myDropzonepopup.options.thumbnail.call(myDropzonepopup, mockFile, imagepath_url);
                                            },
                                            error: function (data) {
                                                myDropzonepopup.options.thumbnail.call(myDropzonepopup, mockFile, siteUrl + '/images/no_image.jpg');
                                            },
                                        })
                                    }
                                    updateForm = 1;
                                });
                                var i = 0;
                                $(formSelector).find('[data-dz-thumbnail]').each(function () {
                                    $(this).wrap("<a class='dropzone_preview_container' title='" + tempImgArray[i] + "' rel='dropzone_preview_group' href='" + imagePath + tempImgArray[i] + "'/>");
                                    i++;
                                });
                                $(".dropzone_preview_container").fancybox({
                                    helpers: {
                                        title: {
                                            type: 'inside'
                                        },
                                    }
                                });
                            }
                        });
                    }
                    this.on('addedfile', function (file) {
                        if (myDropzonepopup.files.length > maxFileLimit) {
                            var _ref;
                            (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                            myDropzonepopup.files.pop();
                            swal(message.module.dropzone.maxLimitMsg.msg.replace('{count}', maxFileLimit));
                        } else {
                            files.push(file.name);
                        }
                    });
                    this.on("removedfile", function (file) {
                        files.splice($.inArray(file.name, file), 1);
                        var oldImageList = $(oldImageSelector).val();
                        var imageArray = oldImageList.split(',');
                        if (file.id) {
                            $.ajax({
                                url: siteUrl + deleteImageUrl,
                                type: "POST",
                                data: {"id": file.id, "imgName": file.name},
                                success: function (data) {
                                    if (data) {
                                        imageArray = jQuery.grep(imageArray, function (value) {
                                            return value != imageArray;
                                        });
                                        $(oldImageSelector).val(imageArray.toString());
                                        notify.pnotify.body(message.module.dropzone.imgDeleteMsg.msg).show();
                                    }
                                }
                            });
                        }
                    });
                },
                sending: function (file, xhr, formData) {
                    var form = $(formSelector);
                    var formvalues = form.serializeArray();
                    $.each(formvalues, function (key, value) {
                        formData.append(value.name, value.value);
                    });
                },
                error: function (file) {
                    if (!file.accepted)
                        this.removeFile(file);
                },
                complete: function (a) {
                    if (a.status == 'success') {
                        var res = $.parseJSON(a.xhr.response);
                        if (res.status == '1') {
                            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                jQuery("#generalModal").modal("hide");
                                notify.pnotify.body(successMsg).show();
                                $.fn.yiiGridView.update(gridIDForRefresh);
                            }
                        }
                    }
                },
            });
        }

    }

};
/* END app */

/* END */





