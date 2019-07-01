<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
	 * @throws Throwable
	 */
	public function run():string {
		$leader = $this->group->leader;
		$leader_role = (null === $leader->id)?'Лидер':ArrayHelper::getValue(RefUserRoles::getUserRolesInGroup($leader->id, $this->group->id), '0.name');
		/*Пока оставляю так, после фиксации условий буду переделывать на AR*/
		$sql = "SELECT rupt.id as 'id',COUNT(rupt.id) as 'count' FROM ref_user_position_types rupt 
		LEFT JOIN rel_ref_user_positions_types rrupt ON rupt.id = rrupt.position_type_id
			LEFT JOIN ref_user_positions rup ON rup.id = rrupt.position_id
			LEFT JOIN sys_users su ON su.`position` = rup.id
			LEFT JOIN rel_users_groups rug ON rug.user_id=su.id
			LEFT JOIN sys_groups sg ON sg.id = rug.group_id
			WHERE sg.id = {$this->group->id}
			GROUP BY rupt.id";

		$positionTypes = ActiveRecord::findBySql($sql)->asArray()->all();
		$positionTypes = ArrayHelper::map($positionTypes, 'id', 'count');
		$positionTypes = array_merge(array_fill_keys(ArrayHelper::getColumn(RefUserPositionTypes::find()->active()->all(), 'id'), $positionTypes));

		/*Строим срез по типам должностей*/

		return $this->render('group_card', [
			'title' => $this->group->name,
			'groupId' => $this->group->id,
			'leader' => (null === $leader->id)?'N/A':$leader->username,
			'leader_role' => $leader_role,
			'userCount' => count($this->group->relUsers),
			'vacancyCount' => count($this->group->relVacancy),
			'positionTypeData' => $positionTypes
		]);
	}
}
