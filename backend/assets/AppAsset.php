<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
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
        '../../public/css/custom.css',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css'
        
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js',
        '../../public/js/dropzone.min.js',
        '../../public/js/tinymce/tinymce.min.js',
        '../../public/js/message.js',
        '../../public/js/sweet-alert.js',
        '../../public/js/swal-forms.js',
        '../../public/js/notification.js',
        '../../public/js/pnotify.js',
        '../../public/js/app.js',
        //'../../public/js/demoUsage.js',
        '../../public/js/jstz-1.0.4.min.js',
        '../../public/js/common.js',    
        'js/language.js',
    ];
    public $depends = [
        
        'yii\bootstrap\BootstrapAsset',
    ];
}
