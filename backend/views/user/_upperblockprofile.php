<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Profile Page Upperblock
 *  
 */
 // echo $directoryAsset;exit;

/* This is userimage Url for Display in app */

$userImage =  \Yii::$app->params['userImageUrl'];
$userThumbImage = \Yii::$app->params['userImageThumbUrl'];  

/*
  @code for if user imageNull then display default image.
  *if user uploaded image when show its profile.
*/

if(strcmp($model->user_image, 'default.jpeg'))
  $userImageFilter = $userImage.$model->user_image; //default image.
else
  $userImageFilter = $userImage.'tmp/'.$model->user_image; //@path : user uploaded image.
?>
<!-- Profile Image -->
<div class="box box-primary">
<div class="box-body box-profile">
  <!-- <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">-->
  <a class="fancybox" rel="group" href="<?php echo $userImageFilter; ?>" data-caption="<?php echo $model->username.' '.$model->last_name?>"> <img class="profile-user-img img-responsive img-circle" src="<?php echo $userImageFilter; ?>" alt="User profile picture"></a>

  <h3 class="profile-username text-center"><?php echo $model->username;?></h3>

  <p class="text-muted text-center"><?php echo $model->email;?></p>

 <!--  <ul class="list-group list-group-unbordered">
      
    <li class="list-group-item">
      <b>Followers</b> <a class="pull-right">1,322</a>
    </li>
    
    <li class="list-group-item">
      <b>Following</b> <a class="pull-right">543</a>
    </li>
    <li class="list-group-item">
      <b>Friends</b> <a class="pull-right">13,287</a>
    </li>
  </ul>
<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
</div>
<!-- /.box-body -->
</div>
