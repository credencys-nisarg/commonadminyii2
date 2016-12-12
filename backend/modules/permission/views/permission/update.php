<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\permission\models\Permission */

$this->title = 'Update Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->permission_id, 'url' => ['view', 'id' => $model->permission_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permission-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'classMethods' => $classMethods, 'userPermission' => $userPermission
    ]) ?>

</div>
