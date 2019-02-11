<?php
declare(strict_types = 1);

namespace app\widgets\group;

use app\modules\groups\models\Groups;
use yii\base\Widget;

/**
 * Class GroupWidget
 *
 * @property Groups $group
 */
class GroupWidget extends Widget {
	public $group;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('group', [
			'group' => $this->group,
			'users' => $this->group->getRelUsers()->joinWith(['relRefUserRoles'])->where(['group_id' => $this->group->id])->orderBy(['ref_user_roles.boss_flag' => SORT_DESC, 'ref_user_roles.id' => SORT_ASC])->all()
		]);
	}
}
