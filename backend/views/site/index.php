<?php
/* @var $this yii\web\View */

$this->title = 'Common Admin Panel';
?>
<!--
    Display Language labels
-->
<div class="languages">
    <?php
        foreach (Yii::$app->params['languages'] as $key => $language){
            echo "<span class='language' id='".$key."'>$language | </span>";
        }
    ?>
</div>
<div class="site-index">

    <div class="jumbotron">
        <!--
            Display Welcome label in different language as per selected
        -->
        <h2><?php echo Yii::t('app',"Welcome",[],Yii::$app->session['lang'])?></h2>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">

            </div>
        </div>

    </div>
</div>
