<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$config = [
    'id' => 'testenv',
    'siteTitle' => 'Luya Tests',
    'remoteToken' => 'testtoken',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'cms',
    'modules' => [
        'luya' => 'luya\Module',
        'admin' => [
            'class' => 'admin\Module',
        ],
        'news' => 'news\Module',
        'newsadmin' => 'newsadmin\Module',
        'errorapi' => [
            'class' => 'errorapi\Module',
            /*
            'recipient' => [
                'you@example.com',
            ]
            */
        ],
        'cms' => [
            'class' => 'cms\Module',
        ],
        'cmsadmin' => [
            'class' => 'cmsadmin\Module',
        ],
        'unitmodule' => [
            'class' => '\tests\data\modules\unitmodule\Module',
        ],
        'urlmodule' => [
            'class' => '\tests\data\modules\urlmodule\Module',
        ],
        'viewmodule' => [
            'class' => '\tests\data\modules\viewmodule\Module',
        ],
        'ctrlmodule' => [
            'class' => '\tests\data\modules\ctrlmodule\Module',
        ],
    ],
    'components' => [
        'request' => [
            'forceWebRequest' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => DB_DSN,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'utf8',
        ],
        /*
        'mail' => [
            'password' => '******',
        ],
        */
    ],
];

return $config;
