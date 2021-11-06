<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users[] $leaders
 * @var int $groupId
 */

use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\components\pozitronik\widgets\BadgeWidget;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => static function() use ($leaders, $groupId) {//todo: можно вынести в отдельный виджет
		$result = [];
		foreach ($leaders as $leader) {
			$result[] = BadgeWidget::widget([
				'models' => RefUserRoles::getUserRolesInGroup($leader->id, $groupId),
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
	'itemsSeparator' => false,
	'badgeOptions' => [
		'class' => "pull-right"
	]
]) ?>