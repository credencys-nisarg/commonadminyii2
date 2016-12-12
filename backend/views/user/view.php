<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\AppHelper;
// @var $this yii\web\View /
// @var $model common\models\User /
$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
<h1><?php echo Html::encode($this->title) ?></h1>
<p>
<?php echo  Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
<?php echo  Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->user_id], [
'class' => 'btn btn-danger',
'data' => [
'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
'method' => 'post',
],
]) ?>
</p>
<?php echo  DetailView::widget([
'model' => $model,
'attributes' => [
'user_id',
'username',
'password',
'ur_id',
'ut_id',
'email:email',
'first_name',
'last_name',
'user_image',
'country_id',
'state_id',
'city_id',
'pincode',
'status',
'created_by',
//'created_on',
[
'label'=>'created_on',
'value'=>AppHelper::displayDateTime($model->created_on),
], // Remove this comment if not used afterfind for conversation date format
'modified_by',
//'modified_on',
[
'label'=>'modified_on',
'value'=>AppHelper::displayDateTime($model->modified_on),
],// Remove this comment if not used afterfind for conversation date format
],
]) ?>
</div>