<?php
/** @noinspection UsingInclusionReturnValueInspection */
declare(strict_types = 1);

use yii\caching\FileCache;
use kartik\grid\Module as GridModule;
use app\models\user\User;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GIIModule;
use yii\swiftmailer\Mailer;
use yii\log\FileTarget;
use /** @noinspection PhpUnusedAliasInspection */
	yii\caching\DummyCache;
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
		'@npm' => '@vendor/npm-asset'
	],
	'modules' => [
		'gridview' =>  [
			'class' => GridModule::class,
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
			'cookieValidationKey' => 'CjhjrNsczxJ,tpmzyD:jgeCeyekb<fyfyf'
		],
		'urlManager' => [
			'class' => UrlManager::class,
			'enablePrettyUrl' => true,
			'showScriptName' => false
		],
		'assetManager' => [
			'appendTimestamp' => false,
			'forceCopy' => false
		],
		'redis' => [
			'class' => '\yii\redis\Connection',
			'hostname' => 'localhost',
			'port' => 6379,
			'database' => 0
		],
		'cache' => [
//			'class' => 'yii\redis\Cache',
//			'class' => FileCache::class,
			'class' => DummyCache::class
		],
		'user' => [
			'identityClass' => User::class,
			'enableAutoLogin' => true
		],
		'errorHandler' => [
			'errorAction' => 'site/error'
		],
		'mailer' => [
			'class' => Mailer::class,
			'useFileTransport' => true
		],
		'log' => [
			'traceLevel' => YII_DEBUG?3:0,
			'targets' => [
				[
					'class' => FileTarget::class,
					'levels' => ['error', 'warning']
				]
			]
		],
		'db' => $db
	],
	'params' => $params
];

if (YII_ENV_DEV) {
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => DebugModule::class,
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => GIIModule::class,
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
