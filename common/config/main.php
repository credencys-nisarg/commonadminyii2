<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'MyComponent' => [
            'class' => 'common\components\MyComponent'
        ],
    ],
];
