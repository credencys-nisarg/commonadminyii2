<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Systemsettings */
/* @var $form yii\widgets\ActiveForm */

$sitelogoimage = \Yii::$app->params['imgLogoUrl'];  

$sitefaviconimage = \Yii::$app->params['imgfaviconUrl']; 

if($model) {
?>
<div class="systemsettings-form">
    <?php
    $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['systemsettings/update']), 'options' => ['id' => 'updatesettings','enctype'=>'multipart/form-data']
    ]);
   
        foreach ($model as $fieldrow) {
            
            $settingtitle = str_replace("_", " ", $fieldrow['name']);
            $settingtitle = ucwords($settingtitle);
            
          if($fieldrow['inputfieldtype'] == 'textarea') {      
          ?> 
        <div class="form-group">      
           <label for="<?php echo $fieldrow['name'] ?>" class="control-label"><?php echo $settingtitle ?></label>
           <textarea name="<?php echo $fieldrow['name'] ?>" maxlength="100" class="form-control" id="<?php echo $fieldrow['name'];?>"><?php echo $fieldrow['val']?></textarea>
        </div>
          <?php } else { ?>
        <div  <?php if($fieldrow['inputfieldtype'] != 'file') {?> class="form-group" <?php } else { ?>  class="form-group col-md-6" <?php } ?> >      
            <label for="<?php echo $fieldrow['name'] ?>" class="control-label"><?php echo $settingtitle ?></label>
            <input type="<?php echo $fieldrow['inputfieldtype']?>" name="<?php echo $fieldrow['name'] ?>" maxlength="100" class="form-control" value="<?php echo $fieldrow['val']?>"  id="<?php echo $fieldrow['name'];?>" <?php if ($fieldrow['inputfieldtype'] != 'file') { ?> required="required" <?php } ?> >
            <?php if($fieldrow['inputfieldtype'] == 'file' && $fieldrow['name'] == 'logo') {?>
            <span ><img src="<?php echo $sitelogoimage.$fieldrow['val'];?>" height="70px" width="70px"></span>
            <?php } ?>
             <?php if($fieldrow['inputfieldtype'] == 'file' && $fieldrow['name'] == 'favicon') {?>
            <span ><img src="<?php echo $sitefaviconimage.$fieldrow['val'];?>" height="70px" width="70px"></span>
            <?php } ?>
        </div> 
          <?php } ?>
    <?php } ?>
    <input type="submit" name="submit" value="Submit" class="btn btn-success" >
    <?php  ActiveForm::end(); ?>
 <?php } else { ?>
    <div class="systemsettings-form"> 
    <div class="form-group">    
        No recodes found.
    </div> 
   </div> 
<?php } ?>
 </div>