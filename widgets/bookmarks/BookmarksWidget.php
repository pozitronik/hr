<?php
declare(strict_types = 1);

namespace app\widgets\bookmarks;

use app\models\user\CurrentUser;
use Throwable;
use yii\base\Widget;

/**
 * Class BookmarksWidget
 * @package app\widgets\bookmarks
 */
class BookmarksWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		BookmarksWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string|null
	 * @throws Throwable
	 */
	public function run():?string {
		if (null === $user = CurrentUser::User()) return null;
		return $this->render('bookmarks',[
			'user' => $user
		]);
	}
}
