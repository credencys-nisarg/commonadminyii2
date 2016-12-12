<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '../../public/css/dropzone.css',
        '../../public/css/pnotify.css',
        '../../public/css/swal-forms.css',
        '../../public/css/sweet-alert.css',
    ];
    public $js = [
        '../../public/js/dropzone.min.js',
        '../../public/js/tinymce/tinymce.min.js',
        '../../public/js/message.js',
        '../../public/js/notification.js',
        '../../public/js/pnotify.js',
        '../../public/js/swal-forms.js',
        '../../public/js/sweet-alert.js',
        '../../public/js/app.js',
        '../../public/js/demoUsage.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
