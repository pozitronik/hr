<?php
declare(strict_types = 1);
/**
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */
use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\references\widgets\roles_select\RolesSelectWidget;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use pozitronik\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;

?>
<?= GridView::widget([
	'dataProvider' => $provider,
	'showFooter' => false,
	'showPageSummary' => false,
	'summary' => '',
	'panel' => false,
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'format' => 'raw',
			'attribute' => 'name',
			'label' => 'Группа',
			'value' => static function(Groups $group) {
				return BadgeWidget::widget([
					'models' => $group->name,
					"badgeOptions" => [
						'class' => "badge badge-info"
					],
					'linkScheme' => ['/home/users', 'UsersSearch[groupId]' => $group->id, 't' => 1]
				]);

			}
		],
		[
			'label' => 'Роли в группе',
			'value' => static function(Groups $group) use ($model) {
				$groupRoles = RefUserRoles::getUserRolesInGroup($model->id, $group->id);
				return (empty($groupRoles)?'Сотрудник':BadgeWidget::widget([
					'models' => $groupRoles,
					'attribute' => 'name',
					'itemsSeparator' => false,
					"optionsMap" => static function() {
						return RefUserRoles::colorStyleOptions();
					}
				]));
			},
			'format' => 'raw'
		]
	]

]) ?>