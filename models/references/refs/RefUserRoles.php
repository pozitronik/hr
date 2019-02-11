<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use kartik\grid\GridView;
use Yii;
use app\modules\groups\models\Groups;
use app\models\references\Reference;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * This is the model class for table "ref_user_roles".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 *
 * custom properties:
 * @property bool $boss_flag
 * @property string $color
 * @property int $usedCount Количество объектов, использующих это значение справочника
 *
 * @property ActiveQuery|RelUsersGroupsRoles[] $relUsersGroupsRoles Связующий релейшен к привязкам пользователей в группы (just via)
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Релейшен к привязке пользователей в группах
 * @property ActiveQuery|Groups[] $groups
 * @property ActiveQuery|Users[] $users
 *
 */
class RefUserRoles extends Reference {
	public $menuCaption = 'Роли пользователей внутри групп';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_roles';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['name'], 'string', 'max' => 256],
			['boss_flag', 'boolean'],
			[['color'], 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted',
			'boss_flag' => 'Лидер',
			'color' => 'Цвет',
			'usedCount' => 'Использований'
		];
	}

	/**
	 * @return RelUsersGroupsRoles[]|ActiveQuery
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['role' => 'id']);
	}

	/**
	 * @return RelUsersGroups[]|ActiveQuery
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['id' => 'user_group_id'])->via('relUsersGroupsRoles');
	}

	/**
	 * @return Groups[]|ActiveQuery
	 */
	public function getGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUsersGroups');
	}

	/**
	 * @return Users[]|ActiveQuery
	 */
	public function getUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
	}

	/**
	 * Возвращает набор ролей для пользователя $user в группе $group
	 * @param int $userId
	 * @param int $groupId
	 * @return self[] array
	 */

	public static function getUserRolesInGroup(int $userId, int $groupId):array {
		return self::find()->joinWith('relUsersGroups')->where(['user_id' => $userId, 'group_id' => $groupId])->all();
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		RelUsersGroupsRoles::updateAll(['role' => $toId], ['role' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array {
		return Yii::$app->cache->getOrSet(static::class."DataOptions", function() {
			/** @var self[] $items */
			$items = self::find()->active()->all();
			$result = [];
			foreach ($items as $key => $item) {
				$result[$item->id] = [
					'data-color' => $item->color,
					'data-boss' => $item->boss_flag
				];
			}
			return $result;
		});
	}

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array {
		return [
			[
				'attribute' => 'id',
				'options' => [
					'style' => 'width:36px;'
				]
			],
			[
				'attribute' => 'boss_flag',
//				'header' => Html::tag('i', false, ['class' => 'fa fa-crown']),
				'value' => function($model) {
					/** @var self $model */
					return $model->boss_flag?Html::tag('i', false, ['class' => 'fa fa-crown']):false;
				},
				'format' => 'raw',
				'options' => [
					'style' => 'width:30px;'
				],
				'headerOptions' => ['style' => 'text-align:center'],
				'filterOptions' => ['style' => 'text-align:center'],
				'contentOptions' => ['style' => 'text-align:center; vertical-align: middle;'],
				'filterType' => GridView::FILTER_CHECKBOX_X
			],
			[
				'attribute' => 'name',
				'value' => function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:Html::tag('span', Html::a($model->name, ['update', 'class' => $model->formName(), 'id' => $model->id]), [
						'style' => "background: {$model->color}"
					]);
				},
				'format' => 'raw'
			],
			'usedCount'
		];
	}

	/**
	 * Поиск по модели справочника
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search(array $params):ActiveQuery {
		/** @var ActiveQuery $query */
		$query = self::find();
		$this->load($params);
		$query->andFilterWhere(['LIKE', 'name', $this->name]);
		$query->andFilterWhere(['=', 'boss_flag', $this->boss_flag]);

		return $query;
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelUsersGroupsRoles::find()->where(['role' => $this->id])->count();
	}
}
