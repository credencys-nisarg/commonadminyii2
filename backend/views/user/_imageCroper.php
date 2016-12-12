<?php
use yii\widgets\ActiveForm;

?>
<!-- Modal -->
<div class="modal" id="myCropModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" data-container="renderForm">
            <?php
            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['user/crop-image']), 'options' => ['id' => 'crop_form',]
            ]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title h4 bold-sm">Crop Image</h4>
            </div>
            <div class="modal-body">
                <img src="" name="User[user_image]" id="user-user_image" height="300px" style="margin-top: 10px;"
                     width="100%">
                <div><input type="hidden" id="imageName" value="" name="imageName"></div>
                <div><input type="hidden" id="imageUrl" value="" name="imageUrl"></div>
                <div><input type="hidden" id="x" value="" name="x"></div>
                <div><input type="hidden" id="y" value="" name="y"></div>
                <div><input type="hidden" id="w" value="" name="w"></div>
                <div><input type="hidden" id="h" value="" name="h"></div>
                <div><input type="hidden" id="image_width" value="" name="image_width"></div>
                <div><input type="hidden" id="image_height" value="" name="image_height"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary" id="js_save_profile_photo">Crop</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>