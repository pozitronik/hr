<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use yii\base\Widget;
use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use pozitronik\helpers\ArrayHelper;
use Throwable;

/**
 * Class GroupSelectWidget
 * @package app\components\group_card
 *
 * @property Groups $group
 * @property bool $short
 */
class GroupCardWidget extends Widget {
	public $group;
	public $short = false;

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
	 * @throws Throwable
	 */
	public function run():string {
		$leader = $this->group->leader;
		$leader_role = (null === $leader->id)?'Лидер':ArrayHelper::getValue(RefUserRoles::getUserRolesInGroup($leader->id, $this->group->id), '0.name');

		/*Строим срез по типам должностей*/

		return $this->render($this->short?'group_info':'group_card', [
			'title' => $this->group->name,
			'groupId' => $this->group->id,
			'leader' => (null === $leader->id)?'N/A':$leader->username,
			'leader_role' => $leader_role,
			'userCount' => count($this->group->relUsers),
			'vacancyCount' => count($this->group->relVacancy),
			'positionTypeData' => $this->group->getGroupPositionTypeData()
		]);
	}
}
