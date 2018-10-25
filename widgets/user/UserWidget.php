<?php
declare(strict_types = 1);

namespace app\widgets\user;

use app\models\users\Users;
use yii\base\Widget;

/**
 * Class UserWidget
 * @property Users $user
 * @property string $mode boss|user|etc - prototype
 *
 */
class UserWidget extends Widget {
	public $user;
	public $mode = 'user';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('user', [
			'model' => $this->user,
			'boss' => 'boss' === $this->mode
		]);

	}
}
