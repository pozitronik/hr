<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\components\pozitronik\widgets\BadgeWidget;
use yii\base\InvalidConfigException;

/**
 * Class UserGraph
 * @package app\modules\graph\models
 */
class UserGraph extends Graph {

	/**
	 * {@inheritDoc}
	 */
	public function __construct(?Users $user = null, $config = []) {
		parent::__construct($config);
		if (null !== $user) $this->buildGraph($user);
	}

	/**
	 * @param Users $user
	 * @throws InvalidConfigException
	 */
	public function buildGraph(Users $user):void {
		$this->nodes[] = new UserNode($user);
		/** @var Groups $group */
		foreach ((array)$user->relGroups as $group) {
			$this->nodes[] = new GroupNode($group);
			$groupRoles = RefUserRoles::getUserRolesInGroup($user->id, $group->id);
			$this->edges[] = new GraphEdge([
				'id' => $user->formName().$user->id.'x'.$group->formName().$group->id,
				'from' => $user->formName().$user->id,
				'to' => $group->formName().$group->id,
				'label' => empty($groupRoles)?'':BadgeWidget::widget([
					'models' => $groupRoles,
					'attribute' => 'name',
					'itemsSeparator' => ', ',
					'useBadges' => false
				])
			]);
		}
	}

}