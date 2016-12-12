<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . $model->username;


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

//print_r($countries);
//exit;
?>
<div class="user-update">
   <!-- Content Wrapper. Contains page content -->
     <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        User Profile
      </h1>
    </section> -->

    <!-- Main content -->
    <section class="content">
        
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
           <?= $this->render('_upperblockprofile', [
                'model' => $model,
               
            ]) ?>

          <!-- /.box -->

          <!-- About Me Box -->
            <?= $this->render('_lowerblockprofile', [
                'model' => $model,
            ]) ?>
          <!-- END about me box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                 <!--Activity Partial-->
              </div>
            <div class="tab-pane" id="settings">
              <!-- Setting Partial here -->
               <?= $this->render('_form', [
                'model' => $model,
                'countries' => $countries,
                'states' => $states,
                'cities' => $cities,
              ]) ?>
              <!-- End Setting Partial -->
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </section>
    <!-- /.content -->
 <!-- /.content-wrapper -->
</div>
