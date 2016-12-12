<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EmailTemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'create_on') ?>

    <?php // echo $form->field($model, 'modify_by') ?>

    <?php // echo $form->field($model, 'modify_on') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
