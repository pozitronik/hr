<?php
declare(strict_types = 1);

/** @noinspection UsingInclusionReturnValueInspection */

use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\export\ExportModule;
use app\modules\grades\models\references\RefGrades;
use app\modules\grades\SalaryModule;
use app\modules\grades\models\references\RefSalaryPremiumGroups;
use app\modules\grades\models\references\RefLocations;
use app\modules\grades\models\references\RefUserPositionBranches;
use app\modules\grades\models\references\RefUserPositionTypes;
use app\modules\groups\GroupsModule;
use app\modules\import\ImportModule;
use app\modules\privileges\PrivilegesModule;
use app\modules\groups\models\references\RefGroupRelationTypes;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\references\ReferencesModule;
use app\modules\grades\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use kartik\grid\Module as GridModule;
use app\models\user\User;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GIIModule;
use yii\redis\Connection;
use yii\swiftmailer\Mailer;
use yii\log\FileTarget;
use yii\redis\Cache as RedisCache;
use /** @noinspection PhpUnusedAliasInspection */
	yii\caching\DummyCache;
use /** @noinspection PhpUnusedAliasInspection */
	yii\caching\FileCache;
use yii\web\UrlManager;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
	'id' => 'basic',
	'language' => 'ru-RU',
	'basePath' => dirname(__DIR__),
	'defaultRoute' => 'site/index',
	'bootstrap' => ['log', 'users', 'groups'],
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset'
	],
	'modules' => [
		'gridview' => [
			'class' => GridModule::class,
			'bsVersion' => 3
			// enter optional module parameters below - only if you need to
			// use your own export download action or custom translation
			// message source
			// 'downloadAction' => 'gridview/export/download',
			// 'i18n' => []
		],
		'import' => [
			'class' => ImportModule::class
		],
		'attributes' => [
			'class' => DynamicAttributesModule::class,
			'params' => [
				'references' => [
					RefAttributesTypes::class
				]
			]
		],
		'users' => [
			'class' => UsersModule::class,
			'params' => [
				'references' => [
					RefUserRoles::class
				]
			]
		],
		'groups' => [
			'class' => GroupsModule::class,
			'params' => [
				'references' => [
					RefGroupTypes::class,
					RefGroupRelationTypes::class
				]
			]
		],
		'references' => [
			'class' => ReferencesModule::class
		],
		'privileges' => [
			'class' => PrivilegesModule::class
		],
		'export' => [
			'class' => ExportModule::class
		],
		'salary' => [
			'class' => SalaryModule::class,
			'params' => [
				'references' => [
					RefUserPositionTypes::class,
					RefUserPositionBranches::class,
					RefSalaryPremiumGroups::class,
					RefLocations::class,
					RefUserPositions::class,
					RefGrades::class
				]
			]
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
			'class' => Connection::class,
			'hostname' => 'localhost',
			'port' => 6379,
			'database' => 0
		],
		'cache' => [
//			'class' => RedisCache::class,
			'class' => FileCache::class,
//			'class' => DummyCache::class
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
