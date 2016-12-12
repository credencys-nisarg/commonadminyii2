<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.10;dbname=yii2advanced',
            'username' => 'remote',
            'password' => 'remote',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'human_resource@credencys.com',
                'password' => 'human_resource',
                'port' => '587',
                'encryption' => 'tls', //ssl //tls,

            ],
        ],
//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => ''
//                ],
//            ],
//        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],*/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => 'false'
        ],
    ],
];
