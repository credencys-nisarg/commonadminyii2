<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EmailTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
    ]); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
