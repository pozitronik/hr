<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecordLoggerSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\history\models\ActiveRecordLoggerSearch;
use app\modules\privileges\models\Privileges;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']).")":" (нет записей)")
	],
	'summary' => null,
	'showOnEmpty' => false,
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
]) ?>
