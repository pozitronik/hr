<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user_select;

use app\modules\users\models\Users;
use app\widgets\select_model\SelectModelWidget;

/**
 * Class UserSelectWidget
 * Виджет списка пользователей (для добавления в группу)
 *
 */
class UserSelectWidget extends SelectModelWidget {
	public $selectModel = Users::class;
	public $ajaxSearchUrl = '/users/ajax/user-search';
	public $mapAttribute = 'username';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserSelectWidgetAssets::register($this->getView());
		$this->postUrl = $this->renderingMode === self::MODE_AJAX?'/users/ajax/users-add-to-group':$this->postUrl;//todo: динамическая ссылка
	}

}
