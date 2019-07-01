<?php
declare(strict_types = 1);

namespace app\widgets\user_card;

use app\modules\users\models\Users;
use Throwable;
use yii\base\Widget;

/**
 * @package app\components\user_card
 *
 * @property Users $user
 */
class UserCardWidget extends Widget {
	public $user;
	public $group;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserCardWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
//		$leader =($this->group->isLeader($this->user));

		return $this->render('user_card', [
			'user' => $this->user
		]);
	}
}
