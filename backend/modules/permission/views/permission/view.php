<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\permission\models\Permission */

$this->title = 'View Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->permission_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->permission_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'permission_id',
            'roleid',
            'userid',
            'action_name:ntext',
            'class_name',
        ],
    ]) ?>

</div>
