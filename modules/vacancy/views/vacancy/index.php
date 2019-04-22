<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\history\models\references\RefModels;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\vacancy\models\VacancySearch;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['вакансия', 'вакансии', 'вакансий']).")":" (нет вакансий)")
	],
	'summary' => null !== $searchModel?Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success summary-content']):null,
	'showOnEmpty' => true,
	'emptyText' => Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success']),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'formatter' => [
		'class' => Formatter::class,
		'nullDisplay' => ''
	],
]) ?>