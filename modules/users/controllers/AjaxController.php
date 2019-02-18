<?php
declare(strict_types = 1);

namespace app\modules\users\controllers;

use app\models\core\ajax\BaseAjaxController;
use app\models\user\CurrentUser;
use app\modules\users\models\Bookmarks;
use Throwable;
use Yii;

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
		$bookmark = new Bookmarks();
		if ($bookmark->load(Yii::$app->request->post(), '')) {
			if (null === $user = CurrentUser::User()) return $this->answer->addError('user', 'Unauthorized');
			$bookmarks = $user->options->bookmarks;
			$bookmarks[] = $bookmark;
			$user->options->bookmarks = $bookmarks;
			return $this->answer->answer;
		}
		return $this->answer->addErrors($bookmark->errors);
	}

	/**
	 * Удаляет закладку
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserRemoveBookmark():array {
		if (false !== $route = Yii::$app->request->post('route', false)) {
			if (null === $user = CurrentUser::User()) $this->answer->addError('user', 'Unauthorized');
			$user->options->bookmarks = array_filter($user->options->bookmarks, function(Bookmarks $bookmark) use ($route) {/*PHP не модифицирует результирующий массив при каждом вызове замыкания, поэтому можно не вводить временную переменную*/
				return $route !== $bookmark->route;
			});

			return $this->answer->answer;
		}
		return $this->answer->addError('route', 'Not found');
	}

//	public function actionAddUserToGroup() {
//		if ((false !== $userId = Yii::$app->request->post('userId', false)) && (false !== $groupId = Yii::$app->request->post('groupId', false))) {
//			$user = Users::findModel($userId);
//			$group = Groups::findModel($groupId);
//
//			$user->setRelGroups([$group->id]);
//		}
//	}
}