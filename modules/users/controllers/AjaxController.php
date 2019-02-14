<?php
declare(strict_types = 1);

namespace app\modules\users\controllers;

use app\models\core\ajax\AjaxAnswer;
use app\models\core\ajax\BaseAjaxController;
use app\models\user\CurrentUser;
use app\modules\users\models\Bookmarks;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class AjaxController
 * Все внутренние аяксовые методы модуля.
 */
class AjaxController extends BaseAjaxController {

	/**
	 * Добавляет закладку текущему пользователю
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserAddBookmark():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		$bookmark = new Bookmarks();
		if ($bookmark->load(Yii::$app->request->post(), '')) {
			if (null === $user = CurrentUser::User()) return $answer->addError('user', 'Unauthorized');
			$bookmarks = $user->options->bookmarks;
			$bookmarks[] = $bookmark;
			$user->options->bookmarks = $bookmarks;
			return $answer->answer;
		}
		return $answer->addErrors($bookmark->errors);
	}

	/**
	 * Удаляет закладку
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserRemoveBookmark():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		if (false !== $route = Yii::$app->request->post('route', false)) {
			if (null === $user = CurrentUser::User()) $answer->addError('user', 'Unauthorized');
			$user->options->bookmarks = array_filter($user->options->bookmarks, function(Bookmarks $bookmark) use ($route) {/*PHP не модифицирует результирующий массив при каждом вызове замыкания, поэтому можно не вводить временную переменную*/
				return $route !== $bookmark->route;
			});

			return $answer->answer;
		}
		return $answer->addError('route', 'Not found');

	}
}