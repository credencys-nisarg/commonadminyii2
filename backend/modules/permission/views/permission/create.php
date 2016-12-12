<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\permission\models\Permission */

$this->title = 'Create Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'classMethods' => $classMethods, 'userPermission' => $userPermission
    ]) ?>

</div>
