<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user_select;

use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\components\pozitronik\widgets\SelectModelWidget;

/**
 * Class UserSelectWidget
 * Виджет списка пользователей (для добавления в группу)
 *
 */
class UserSelectWidget extends SelectModelWidget {
	public $selectModel = Users::class;
	public $jsPrefix = 'Users';
	public $mapAttribute = 'username';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserSelectWidgetAssets::register($this->getView());
		$this->ajaxSearchUrl = UsersModule::to('ajax/user-search');
		$this->postUrl = $this->renderingMode === self::MODE_AJAX?UsersModule::to('ajax/users-add-to-group'):$this->postUrl;
		$this->options['placeholder'] = 'Выберите пользователя';
	}

}
