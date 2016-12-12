<?php
use yii\helpers\Html;
use common\helper\AppHelper;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}

// if (Yii::$app->controller->action->id === 'requestpasswordreset' OR 'resetpassword') 
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Credencys</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

<?php
echo $this->render(
        'main-login',
        ['content' => $content]
    ); 
?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

