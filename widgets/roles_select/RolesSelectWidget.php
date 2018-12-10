<?php
declare(strict_types = 1);

namespace app\widgets\roles_select;

use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersGroupsRoles;
use yii\base\Widget;

/**
 * @package app\components\roles_select
 *
 * @property array $data
 * @property array $value
 * @property int $groupId
 * @property int $userId
 */
class RolesSelectWidget extends Widget {
	public $data;
	public $value;
	public $groupId;
	public $userId;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		RolesSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('roles_select', [
			'data' => $this->data??RefUserRoles::mapData(),
			'value' => $this->value??RelUsersGroupsRoles::getRoleIdInGroup($this->userId, $this->groupId),
			'userId' => $this->userId,
			'groupId' => $this->groupId
		]);
	}
}
