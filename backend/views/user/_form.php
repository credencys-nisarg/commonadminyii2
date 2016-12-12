<style>
    .dropzone {
        min-height: 160px;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use yii\helpers\Url;

//we are used name namespaces
use common\models\Countries;
use common\models\States;
use common\models\Cities;
use kartik\select2\Select2;
use common\models\User;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<!--Rajesh jcrop implementation on 13/10/2016-->
<?= $this->registerJsFile(Yii::$app->request->BaseUrl . '/js/user.js', ['depends' => [yii\web\JqueryAsset::className()]]) ?>
<script>
    profileImagePath = '<?php echo Url::base() . "/../../public/upload/user/tmp/";?>';
</script>
<div class="user-form">
    <!--    Renders Cropbox modal-->
    <?= $this->render('_imageCroper', []) ?>
    <?php $form = ActiveForm::begin([
        //'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <!-- Start Nisarg Patel
         12/10/16
    DropZone Added to upload profile image -->

    <?= Html::activeLabel($model, 'user_image') ?>
    <?php if (class_exists("kato\DropZone")) { ?>
        <?= \kato\DropZone::widget([
            'options' => [
                'url' => "profile-image-upload",
                'dictDefaultMessage' => 'Drop Profile Image here to upload',
                'acceptedFiles' => 'image/*',
                'addRemoveLinks' => 'Remove',
                'maxFiles' => 1,
                'maxFilesize' => '2',
            ],
            'clientEvents' => [
                'complete' => "function(file){if(file.status == 'success'){applyCropping(file);}}",
                'removedfile' => "function(file){alert(file.name + ' is removed')}"
            ],
        ]);
    } else {
        echo "<font color='red'>Please Update Composer to use dropzone</font>";
    }
    ?>
    <!-- End Nisarg Patel Dropzone -->

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <!-- hide password field in update time -->
    <?php 
    if($model->isNewRecord)
    { 
        echo $form->field($model, 'password')->passwordInput(['maxlength' => true]);
    }else{ /*not showing password field in update profile data*/ }
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?php
        $dataCountries = ArrayHelper::map(Countries::find()->all(),'country_id','country_name');
        echo $form->field($model, 'country_id')->widget(Select2::classname(), [
            'data' => $dataCountries,
            'language' => 'de',
            'options' => ['placeholder' => 'Select a country ...',
                            'onchange'=>'
                            $.post( "'.Yii::$app->urlManager->createUrl('user/state-lists?id=').'"+$(this).val(), function( data ) {
                            $( "select#user-state_id" ).html( data );
                            });
                            '
                        ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <!-- select2 state list -->
     <?php
     if(isset($model->country_id)){
        $dataState = ArrayHelper::map(States::find()->where(['country_id'=>$model->country_id])->all(),'state_id','state_name');
     }else{
        $dataState = array();
     }
        
        echo $form->field($model, 'state_id')->widget(Select2::classname(), [
            'data' => $dataState,
            'language' => 'de',
            'options' => ['placeholder' => 'Select a state ...',
                            'onchange'=>'
                            $.post( "'.Yii::$app->urlManager->createUrl('user/city-lists?id=').'"+$(this).val(), function( data ) {
                            $( "select#user-city_id" ).html( data );
                            });'
                        ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?php
        if(isset($model->state_id)){
            $dataCity = ArrayHelper::map(Cities::find()->where(['state_id'=>$model->state_id])->all(),'city_id','city_name');
        }else{
            $dataCity = array();
        }
        
        echo $form->field($model, 'city_id')->widget(Select2::classname(), [
            'data' => $dataCity,
            //'value'=> [] 
            'language' => 'de',
            'options' => ['placeholder' => 'Select a city ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?= $this->registerJsFile(Yii::$app->request->BaseUrl . '/js/jquery.Jcrop.js', ['depends' => [yii\web\JqueryAsset::className()]]) ?>
<link rel="stylesheet" type="text/css" href="<?= Yii::$app->request->BaseUrl; ?>/css/jquery.Jcrop.css"/>