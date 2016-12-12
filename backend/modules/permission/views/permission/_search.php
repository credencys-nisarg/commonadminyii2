<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\permission\models\PermissionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'permission_id') ?>

    <?= $form->field($model, 'roleid') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'action_name') ?>

    <?= $form->field($model, 'class_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
