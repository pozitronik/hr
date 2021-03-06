<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\modules\groups\models\Groups;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\users\models\Users;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\web\NotFoundHttpException;

/**
 * Class TargetsSearch
 * @package app\modules\targets\models
 */
class TargetsSearch extends Targets {
	public $parent_name;
	public $group_name;
	public $user_name;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name', 'comment', 'type', 'result_type', 'parent_name', 'group_name', 'user_name'], 'safe']
		];
	}

	/**
	 * Все цели
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search(array $params):ActiveDataProvider {
		$query = Targets::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'name',
				'type',
				'result_type',
				'parent_name' => [
					'asc' => ['parentTarget.name' => SORT_ASC],
					'desc' => ['parentTarget.name' => SORT_DESC]
				],
				'group_name' => [
					'asc' => ['sys_groups.name' => SORT_ASC],
					'desc' => ['sys_groups.name' => SORT_DESC]
				],
				'user_name' => [
					'asc' => ['sys_users.name' => SORT_ASC],
					'desc' => ['sys_users.name' => SORT_DESC]
				]
			]
		]);

		$this->load($params);

		if (!$this->validate()) return $dataProvider;

		$query->joinWith(['relGroups', 'relUsers', 'relParentTarget as parentTarget', 'relTargetsTypes', 'relTargetsResults']);

//		$query->distinct();

		$query->andFilterWhere(['sys_users.id' => $this->id])
			->andFilterWhere(['like', 'parentTarget.name', $this->parent_name])
			->andFilterWhere(['like', 'sys_users.username', $this->user_name])
			->andFilterWhere(['like', 'sys_groups.name', $this->group_name])
//			->andFilterWhere(['in', 'IFNULL(`rel_user_position_types`.`position_type_id`, `rel_ref_user_positions_types`.`position_type_id`)', $this->positionType])//таким образом решаем проблему фильтрации по типу должности, не вводя промежуточную вью.
			->andFilterWhere(['in', 'ref_targets_types.id', $this->type])
			->andFilterWhere(['in', 'ref_targets_results.id', $this->result_type]);

//		Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}

	/**
	 * Поиск целей пользователя
	 * Логика: все цели всех команд пользователя + все цели, назначенные пользователю непосредственно
	 * @param int $userId
	 * @param array $params
	 * @return DataProviderInterface
	 * @throws Throwable
	 *
	 * Логика: находим все ВЕХИ через справочник (их вес на единицу больше ЦЕЛЕЙ, вес которых нулевой).
	 * Фильтруем по пользователям и группам целей (where relTargets.groups => $userCommandId...)
	 * Отдаём целив во вьюху
	 * Во вьюхе выводим цели в колонке через релейшены $model->q1->targets (логику релейшенов надо написать).
	 */
	public function findUserTargets(int $userId, array $params):DataProviderInterface {
		if (null === $user = Users::findModel($userId, new NotFoundHttpException())) return null;

		$userTargetsScope = self::FindUserTargetsScope($user);

		$allUserMilestones = array_unique(ArrayHelper::getColumn($userTargetsScope->all(), 'relParentTarget.id'));

		$models = Targets::find()
			->active()//получаем массив всех вышестоящих целей
			->where(['id' => $allUserMilestones])
			->andFilterWhere(['like', 'sys_targets.name', $this->name])->all();

		/*если у пользователя есть цели без вышестоящей привязки, пихнём их в виртуальную цель */
		if ([] !== $nullParents = $userTargetsScope->joinWith('relParentTarget parent')->andWhere(['parent.id' => null])->all()) {
			$virtualParent = new VirtualTarget([
				'name' => 'Без привязки',
				'type' => ArrayHelper::getValue(RefTargetsTypes::final(), 'parent.id'),
				'relChildTargets' => $nullParents
			]);

			$models[] = $virtualParent;
		}

		$dataProvider = new ArrayDataProvider([
			'allModels' => $models
		]);
		$this->load($params);
		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}

	/**
	 * Все цели группы
	 * @param int $groupId
	 * @param array $params
	 * @return DataProviderInterface
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function findGroupTargets(int $groupId, array $params):DataProviderInterface {
		if (null === $group = Groups::findModel($groupId, new NotFoundHttpException())) return null;

		$groupTargetsScope = self::FindGroupTargetsScope($group);

		$allGroupMilestones = array_unique(ArrayHelper::getColumn($groupTargetsScope->all(), 'relParentTarget.id'));

		$models = Targets::find()->active()
			->where(['id' => $allGroupMilestones])
			->andFilterWhere(['like', 'sys_targets.name', $this->name])
			->andFilterWhere(['like', 'sys_groups.name', $this->group_name])->all();

		/*если у группы есть цели без вышестоящей привязки, пихнём их в виртуальную цель */
		if ([] !== $nullParents = $groupTargetsScope->joinWith('relParentTarget parent')->andWhere(['parent.id' => null])->all()) {
			$virtualParent = new VirtualTarget([
				'name' => 'Без привязки',
				'type' => ArrayHelper::getValue(RefTargetsTypes::final(), 'parent.id'),
				'relChildTargets' => $nullParents
			]);

			$models[] = $virtualParent;
		}

		$this->load($params);

		$dataProvider = new ArrayDataProvider([
			'allModels' => $models
		]);
		$this->load($params);
		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}

	/**
	 * Возвращает цели, зеркальные для пользователя
	 * @param int $userId
	 * @param array $params
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function findUserMirroredTargets(int $userId, array $params):ActiveDataProvider {
		if (null === $user = Users::findModel($userId, new NotFoundHttpException())) return null;

		$allUserTargets = self::FindUserTargetsScope($user)->all();
		$allMirroredTargetsId = [];
		foreach ($allUserTargets as $userTarget) {//довольно кривое временное решение
			if ($userTarget->isMirrored) $allMirroredTargetsId[] = $userTarget->id;
		}

		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => Targets::find()->active()
				->where(['id' => $allMirroredTargetsId])
				->andFilterWhere(['like', 'sys_targets.name', $this->name])
		]);

		if (!$this->validate()) return $dataProvider;

		return $dataProvider;
	}
}