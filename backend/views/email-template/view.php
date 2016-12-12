<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\EmailTemplate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'code',
            'content:ntext',
            'created_by',
            [
            'label'=>'created_on',
            'value'=>AppHelper::displayDateTime($model->created_on),
            ], // Remove this comment if not used afterfind for conversation date format
            'modified_by',
            [
            'label'=>'modified_on',
            'value'=>AppHelper::displayDateTime($model->modified_on),
            ],// Remove this comment if not used afterfind for conversation date format
        ],
    ]) ?>

</div>
