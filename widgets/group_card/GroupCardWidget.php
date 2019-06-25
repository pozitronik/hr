<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use pozitronik\helpers\ArrayHelper;
use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *GroupCard* на нужное нам имя, и работаем
 * @package app\components\group_card
 *
 * @property Groups $group
 */
class GroupCardWidget extends Widget {
	public $group;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupCardWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$leader = $this->group->leader;
		$leader_role = (null === $leader->id)?'Лидер':ArrayHelper::getValue(RefUserRoles::getUserRolesInGroup($leader->id, $this->group->id), '0.name');

		return $this->render('group_card', [
			'title' => $this->group->name,
			'leader' => (null === $leader->id)?'N/A':$leader->username,
			'leader_role' => $leader_role
		]);
	}
}
