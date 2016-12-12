<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Systemsettings */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Systemsettings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="systemsettings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
    