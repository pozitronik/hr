<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\bookmarks;

use app\models\core\CachedWidget;
use app\models\user\CurrentUser;
use app\modules\users\models\Bookmarks;
use Throwable;
use Yii;
use yii\helpers\Url;

/**
 * Class BookmarksWidget
 * @package app\widgets\bookmarks
 */
class BookmarksWidget extends CachedWidget {

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
			'bookmarks' => $user->options->bookmarks,
			'currentBookmark' => new Bookmarks([
				'route' => Url::current(),
				'name' => $this->view->title?:Yii::$app->requestedRoute,
				'type' => Bookmarks::TYPE_DEFAULT
			])
		]);
	}
}
