<?php

use app\models\user\User;
use yii\swiftmailer\Mailer;
use yii\log\FileTarget;
use yii\caching\DummyCache;
use yii\web\UrlManager;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'defaultRoute' => 'site/index',
	'bootstrap' => ['log'],
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'modules' => [
		'gridview' =>  [
			'class' => '\kartik\grid\Module',
			'bsVersion' => 3
			// enter optional module parameters below - only if you need to
			// use your own export download action or custom translation
			// message source
			// 'downloadAction' => 'gridview/export/download',
			// 'i18n' => []
		]
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'CjhjrNsczxJ,tpmzyD:jgeCeyekb<fyfyf',
		],
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => true,
			'showScriptName' => false,
		],
		'assetManager' => [
			'appendTimestamp' => false,
			'forceCopy' => true
		],
		'redis' => [
			'class' => '\yii\redis\Connection',
			'hostname' => 'localhost',
			'port' => 6379,
			'database' => 0,
		],
		'cache' => [
//			'class' => 'yii\redis\Cache',
//			'class' => 'yii\caching\FileCache',
			'class' => DummyCache::class,
		],
		'user' => [
			'identityClass' => User::class,
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => Mailer::class,
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG?3:0,
			'targets' => [
				[
					'class' => FileTarget::class,
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => $db,
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
