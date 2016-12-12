<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\permission\models\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permission', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'permission_id',
            'roleid',
            'userid',
            'action_name:ntext',
            'class_name',
            [
                'class' => 'yii\grid\ActionColumn',
                //'contentOptions' => ['style' => 'width:260px;'],
                'header' => 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        //$url = \yii\helpers\Url::to(['permission/index', 'id' => $model->userid]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),                                  
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
