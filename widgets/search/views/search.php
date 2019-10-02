<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use app\modules\groups\GroupsModule;
use kartik\typeahead\Typeahead;
use yii\web\JsExpression;
use yii\web\View;

//Html::a('Дашборд', HomeModule::to(['/home', 'u' => '{{id}}']), ['class' => 'btn btn-info summary-content'])
$groupTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div></div>';
$userTemplate = '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="suggestion-links"><a href="/users/users/profile?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Профиль<a/><a href="/home?u={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Дашборд<a/></div><div class="clearfix"></div></div>';
?>
<?= Typeahead::widget([
	'container' => [
		'class' => 'pull-left search-box'
	],
	'name' => 'search',
	'options' => ['placeholder' => 'Поиск'],
	'pluginOptions' => ['highlight' => true],
	'pluginEvents' => [
		"typeahead:select" => "function(e, o) {open_result(o)}",
		"typeahead:close" => "function(e, o) {open_result(o)}"
	],
	'dataset' => [
		[
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
			'display' => 'name',
			'templates' => [
				'suggestion' => new JsExpression("Handlebars.compile('{$groupTemplate}')"),
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
				'suggestion' => new JsExpression("Handlebars.compile('{$userTemplate}')"),
				'header' => '<h3 class="suggestion-header">Пользователи</h3>'
			],
			'remote' => [
				'url' => GroupsModule::to(['ajax/search-users']).'?term=%QUERY',
				'wildcard' => '%QUERY'
			]
		]
	]
]) ?>
