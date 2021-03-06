<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user;

use app\components\pozitronik\cachedwidget\CachedWidget;
use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;

/**
 * Class UserWidget
 * @property Users $user
 * @property Groups $group
 * @propery string $view default view
 */
class UserWidget extends CachedWidget {
	public $user;
	public $group;
	public $view = 'user';

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
		if (null === $this->group) {
			return $this->render($this->view, [
				'model' => $this->user
			]);
		}

		return $this->render('leader', [//think: сокращёная форма имени?
			'model' => $this->user,
			'group' => $this->group,
			'options' => static function() {
				return RefUserRoles::colorStyleOptions();
			}
		]);

	}
}
