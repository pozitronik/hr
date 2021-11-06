<?php
declare(strict_types = 1);

namespace app\modules\users\models\references;

use app\components\pozitronik\references\models\CustomisableReference;
use app\components\pozitronik\references\ReferencesModule;
use app\modules\users\UsersModule;
use app\components\pozitronik\badgewidget\BadgeWidget;
use kartik\grid\GridView;
use app\modules\groups\models\Groups;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\modules\users\models\Users;
use Throwable;
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
 * @property bool $importance_flag
 * @property string $color
 * @property int $usedCount Количество объектов, использующих это значение справочника
 *
 * @property ActiveQuery|RelUsersGroupsRoles[] $relUsersGroupsRoles Связующий релейшен к привязкам пользователей в группы (just via)
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Релейшен к привязке пользователей в группах
 * @property ActiveQuery|Groups[] $groups
 * @property ActiveQuery|Users[] $users
 *
 */
class RefUserRoles extends CustomisableReference {
	public $menuCaption = 'Роли пользователей внутри групп';
	public $menuIcon = false;

	protected $_dataAttributes = ['color', 'textcolor', ['boss' => 'boss_flag']];

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
			[['name', 'color', 'textcolor'], 'string', 'max' => 256],
			[['boss_flag', 'importance_flag'], 'boolean']
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
			'importance_flag' => 'Важная шишка',
			'color' => 'Цвет фона',
			'textcolor' => 'Цвет текста',
			'usedCount' => 'Использований'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['role' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['id' => 'user_group_id'])->via('relUsersGroupsRoles');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUsersGroups');
	}

	/**
	 * @return ActiveQuery
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
		return self::find()->joinWith('relUsersGroups', false)->where(['user_id' => $userId, 'group_id' => $groupId])->all();
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws Throwable
	 */
	public static function merge(int $fromId, int $toId):void {
		RelUsersGroupsRoles::updateAll(['role' => $toId], ['role' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
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
				'value' => static function($model) {
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
				'attribute' => 'importance_flag',
				'value' => static function($model) {
					/** @var self $model */
					return $model->importance_flag?Html::tag('i', false, ['class' => 'fa fa-badge-check']):false;
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
				'value' => static function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount',
				'filter' => false,
				'value' => static function($model) {
					/** @var self $model */
					return BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'usedCount',
						'linkScheme' => [UsersModule::to(['users/index']), 'UsersSearch[roles][]' => $model->id],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw'
			]
		];
	}

	/**
	 * Поиск по модели справочника
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search(array $params):ActiveQuery {
		$query = self::find();
		$this->load($params);
		$query->andFilterWhere(['LIKE', 'name', $this->name]);
		$query->andFilterWhere(['=', 'boss_flag', $this->boss_flag]);
		$query->andFilterWhere(['=', 'importance_flag', $this->importance_flag]);

		return $query;
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelUsersGroupsRoles::find()->where(['role' => $this->id])->count();
	}

}
