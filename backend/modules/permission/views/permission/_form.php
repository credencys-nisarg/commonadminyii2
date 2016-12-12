<?php

use Yii;
use yii\web\View;
use yii\base\Controller;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\permission\models\Permission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'roleid')->dropDownList([ '1' => 'Admin', '2' => 'Users'], ['prompt' => 'Select Role']) ?>
    <?= $form->field($model, 'userid')->dropDownList([ '1' => 'Sandeep Paneliya'], ['prompt' => 'Select User']) ?>
    <?php 
        echo Yii::$app->controller->renderPartial('_permission',['classMethods' => $classMethods, 'userPermission' => $userPermission]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$jsPath = Yii::getAlias('@web').DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'permission.js';
$this->registerJsFile($jsPath, ['depends' => [yii\web\JqueryAsset::className()]]);
?>
