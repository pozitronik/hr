<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use app\assets\AppAsset;
use app\modules\groups\GroupsModule;
use kartik\typeahead\Typeahead;
use yii\web\JsExpression;
use yii\web\View;

$template = '<div class="suggestion-item"><p class="suggestion-name">{{name}}</p>';
?>
<?= Typeahead::widget([
	'container' => [
		'class' => 'pull-left search-box'
	],
	'name' => 'search',
	'options' => ['placeholder' => 'Поиск'],
	'pluginOptions' => ['highlight' => true],
	'pluginEvents' => [
		"typeahead:select" => "function(e, o) {open_result(o)}"
	],
	'dataset' => [
		[
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$template}')"),
				'header' => '<h3 class="suggestion-header">Группы</h3>'
			],
			'remote' => [
				'url' => GroupsModule::to(['ajax/search-groups']).'?term=%QUERY',
				'wildcard' => '%QUERY'
			]
		],
		[
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$template}')"),
				'header' => '<h3 class="suggestion-header">Пользователи</h3>'
			],
			'remote' => [
				'url' => GroupsModule::to(['ajax/search-users']).'?term=%QUERY',
				'wildcard' => '%QUERY'
			]
		]
	]
]) ?>