<?php
declare(strict_types = 1);

namespace app\modules\groups\controllers;

use app\models\relations\RelGroupsGroups;
use app\models\user\CurrentUser;
use pozitronik\helpers\ArrayHelper;
use app\models\core\ajax\BaseAjaxController;
use app\models\relations\RelUsersGroups;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use Throwable;
use Yii;
use yii\db\Expression;

/**
 * Class AjaxController
 * @package app\modules\groups\controllers
 */
class AjaxController extends BaseAjaxController {

	/**
	 * Генерит и отдаёт вьюшеньку с инфой о группе
	 */
	public function actionGetGroupInfo():array {
		if (null === ($group = Groups::findModel(Yii::$app->request->post('groupid')))) {
			return $this->answer->addError('group', 'Not found');
		}
		$this->answer->content = $this->renderPartial('get-group-info', [
			'group' => $group
		]);
		return $this->answer->answer;
	}

	/**
	 * AJAX user search
	 * @return array
	 */
	public function actionUsersSearch():array {
		$searchModel = new UsersSearch();
		$allowedGroups = [];
		//Проверяем доступы к списку юзеров
		$searchArray = [//Быстрый костыль для демо
			'UsersSearch' => Yii::$app->request->post()
		];
		$dataProvider = $searchModel->search($searchArray, $allowedGroups, false);
		$result = [];
		/** @var Users $model */
		foreach ($dataProvider->models as $model) {
			$result[] = [
				'username' => $model->username,
				'groups' => ArrayHelper::getColumn($model->relGroups, 'id')
			];
		}
		$this->answer->count = $dataProvider->totalCount;
		$this->answer->items = $result;
		return $this->answer->answer;
	}

	/**
	 * Поиск группы в Select2
	 *
	 * @param string|null $term Строка поиска
	 * @param int $page Номер страницы (не поддерживается, задел на быдущее)
	 * @param int|null $user Пользователь ИСКЛЮЧАЕМЫЙ из поиска
	 * @return array
	 * @throws Throwable
	 */
	public function actionGroupSearch(?string $term = null, ?int $page = 0, ?int $user = null):array {
		$out = ['results' => ['id' => '', 'text' => '']];
		$results = [];
		if (null !== $term) {
			/** @var Groups[] $groups */
			$groups = Groups::find()->distinct()/*->select(['sys_groups.id', 'sys_groups.name as text'])*/
			->where(['like', 'sys_groups.name', $term])->andWhere(['not', ['sys_groups.id' => RelUsersGroups::find()->select('group_id')->where(['user_id' => $user])]])->offset(20 * $page)->limit(20)->all();
			foreach ($groups as $group) {
				$results[] = [
					'id' => $group->id,
					'text' => $group->name,
					'logo' => $group->logo,
					'typename' => ArrayHelper::getValue($group->relGroupTypes, 'name'),
					'typecolor' => ArrayHelper::getValue($group->relGroupTypes, 'color')
				];
			}
			$out['results'] = $results;
		}
		return $out;
	}

	/**
	 * Глобальный поиск по группам в скопе групп пользователя
	 * @param string|null $term
	 * @return array
	 */
	public function actionSearchGroups(?string $term):array {
		$this->answer->items = Groups::find()->select(['name', 'id', new Expression("'group' as 'type'")])->distinct()->where(['like', 'sys_groups.name', $term])
			->andWhere(['in', 'sys_groups.id', RelUsersGroups::find()->select('group_id')->where(['user_id' => CurrentUser::Id()])])
			->asArray()->all();

		return $this->answer->items;
	}

	/**
	 * Глобальный поиск по пользователям в скопе групп пользователя
	 * @param string|null $term
	 * @return array
	 */
	public function actionSearchUsers(?string $term):array {
		$this->answer->items = Users::find()->select(['username as name', 'id', new Expression("'user' as 'type'")])->distinct()->where(['like', 'sys_users.username', $term])
			->asArray()->all();
		return $this->answer->items;
	}

	/**
	 * Разрывает связь между двумя группами
	 * @return array
	 */
	public function actionGroupsUnlink():array {
		if ((null === $parentId = Yii::$app->request->post('parentId')) || (null === $childId = Yii::$app->request->post('childId'))) return $this->answer->addError('parameters', 'Not enough');
		if (null === $parentGroup = Groups::findModel($parentId)) return $this->answer->addError('parentId', 'Not found');
		if (null === $childGroup = Groups::findModel($childId)) return $this->answer->addError('childId', 'Not found');
		if (null === RelGroupsGroups::findOne(['parent_id' => $parentId, 'child_id' => $childId])) return $this->answer->addError('link', 'Not linked');
		$parentGroup->setDropChildGroups([$childGroup]);
		return $this->answer->answer;
	}
}