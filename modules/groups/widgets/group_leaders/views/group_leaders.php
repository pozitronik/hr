<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options
 */

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => static function() use ($group) {//todo: можно вынести в отдельный виджет
		$result = [];
		foreach ($group->leaders as $leader) {
			$result[] = BadgeWidget::widget([
				'models' => RefUserRoles::getUserRolesInGroup($leader->id, $group->id),
				'attribute' => 'name',
				'useBadges' => true,
				'itemsSeparator' => false,
				"optionsMap" => RefUserRoles::colorStyleOptions(),
				'prefix' => BadgeWidget::widget([
						'models' => $leader,
						'useBadges' => false,
						'attribute' => 'username',
						'unbadgedCount' => false,
						'itemsSeparator' => false,
						'linkScheme' => [UsersModule::to(['users/profile']), 'id' => $leader->id]
					]).': ',
				'linkScheme' => [UsersModule::to(), 'UsersSearch[roles]' => 'id']
			]);
		}
		return $result;
	},
	'itemsSeparator' => "<span class='pull-right'>,&nbsp;</span>",
	'badgeOptions' => [
		'class' => "pull-right"
	]
]) ?>