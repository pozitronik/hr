<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\roles_select;

use app\components\pozitronik\cachedwidget\CachedWidget;
use app\modules\users\models\references\RefUserRoles;
use app\models\relations\RelUsersGroupsRoles;

/**
 * @package app\components\roles_select
 *
 * @property array $data
 * @property array $value
 * @property int $groupId
 * @property int $userId
 * @property bool $showStatus
 */
class RolesSelectWidget extends CachedWidget {
	public $data;
	public $value;
	public $groupId;
	public $userId;
	public $showStatus = true;

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
			'groupId' => $this->groupId,
			'options' => RefUserRoles::dataOptions(),
			'showStatus' => $this->showStatus
		]);
	}
}
