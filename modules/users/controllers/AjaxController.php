<?php
declare(strict_types = 1);

namespace app\modules\users\controllers;

use app\models\relations\RelUsersGroups;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\users\models\Bookmarks;
use app\modules\users\models\Users;
use app\models\core\controller\BaseAjaxController;
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
			$user->options->bookmarks = array_filter($user->options->bookmarks, static function(Bookmarks $bookmark) use ($route) {/*PHP не модифицирует результирующий массив при каждом вызове замыкания, поэтому можно не вводить временную переменную*/
				return $route !== $bookmark->route;
			});

			return $this->answer->answer;
		}
		return $this->answer->addError('route', 'Not found');
	}

	/**
	 * Запоминает настройку пользователя
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserSetOption():array {
		if (false !== $key = Yii::$app->request->post('key', false)) {
			if (null === $user = CurrentUser::User()) $this->answer->addError('user', 'Unauthorized');
			$value = Yii::$app->request->post('value', []);
			$user->options->set((string)$key, (array)$value);
			return $this->answer->answer;
		}
		return $this->answer->addError('key', 'Not specified');
	}

	/**
	 * Возвращает настройку пользователя
	 * @return array
	 * @throws Throwable
	 */
	public function actionUserGetOption():array {
		if (false !== $key = Yii::$app->request->post('key', false)) {
			if (null === $user = CurrentUser::User()) $this->answer->addError('user', 'Unauthorized');
			return $user->options->get((string)$key);
		}
		return $this->answer->addError('key', 'Not specified');
	}

	/**
	 * Поиск пользователя в Select2
	 *
	 * @param string|null $term Строка поиска
	 * @param int $page Номер страницы (не поддерживается, задел на будущее)
	 * @param int|null $group Группа ИСКЛЮЧАЕМАЯ из поиска
	 * @return array
	 */
	public function actionUserSearch(?string $term = null, ?int $page = 0, ?int $group = null):array {
		$out = ['results' => ['id' => '', 'text' => '']];
		if (null !== $term) {
			$data = Users::find()->distinct()->select(['sys_users.id', 'sys_users.username as text'/*, 'ref_user_positions.name as position_name'*/])/*->joinWith('relUserPositions')*/
			->where(['like', 'sys_users.username', $term])->andWhere(['not', ['sys_users.id' => RelUsersGroups::find()->select('user_id')->where(['group_id' => $group])]])->offset(20 * $page)->limit(20)->asArray()->all();
			$out['results'] = array_values($data);
		}
		return $out;
	}

	/**
	 * Добавляет пользователей в группу. Список пользователей приходит массивом из user_select.js:ajax_post
	 * @return array
	 * @throws Throwable
	 */
	public function actionUsersAddToGroup():array {
		$groupId = Yii::$app->request->post('groupId', false);
		$userId = Yii::$app->request->post('userId', false);
		if (!($groupId && $userId)) {
			return $this->answer->addError('parameters', 'Not enough parameters');
		}
		/** @var Groups $group */
		if (null === ($group = Groups::findModel($groupId))) {
			return $this->answer->addError('group', 'Not found');
		}

		RelUsersGroups::linkModels($userId, $group);
		return $this->answer->answer;
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