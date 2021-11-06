<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\users\UsersModule;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\salary\models\relations\RelGradesPositionsRules;
use app\modules\salary\models\relations\RelRefUserPositionsBranches;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\Users;
use app\components\pozitronik\references\models\CustomisableReference;
use app\components\pozitronik\references\ReferencesModule;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\components\pozitronik\widgets\BadgeWidget;
use Throwable;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Справочник должностей с настройками атрибутов
 *
 * @property int $id
 * @property string $name Название
 * @property int $usedCount Количество объектов, использующих это значение справочника
 * @property string $color
 * @property int $deleted
 *
 * @property RelRefUserPositionsBranches|ActiveQuery $relRefUserPositionsBranches
 * @property RefUserPositionBranches|ActiveQuery $relRefUserPositionBranch
 *
 * @property RelRefUserPositionsTypes[]|ActiveQuery $relRefUserPositionsTypes
 * @property RefUserPositionTypes[]|ActiveQuery $relRefUserPositionTypes
 * @property RelGradesPositionsRules[]|ActiveQuery $relGradesPositionsRules
 * @property RefGrades[]|ActiveQuery $relRefGrades Грейды, разрешённые для этой должности
 * @property $relGrades
 * @property null|int $branch
 * @property null|int[] $types
 * @property null|int[] $grades
 *
 */
class RefUserPositions extends CustomisableReference {
	public $menuCaption = 'Должности';
	public $menuIcon = false;

	public $branchId;//search attributes
	public $typesId;
	public $gradesId;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_positions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id'], 'integer'],
			[['deleted'], 'boolean'],
			[['name', 'color'], 'string', 'max' => 256],
			[['branch', 'types', 'grades', 'relRefGrades'], 'safe'],//relational attributes
			[['branchId', 'typesId', 'gradesId'], 'safe'],//search attributes
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'color' => 'Цвет',
			'deleted' => 'Deleted',
			'usedCount' => 'Использований',
			'branchName' => 'Ветвь',
			'branch' => 'Ветвь',
			'branchId' => 'Ветвь',
			'types' => 'Типы',
			'relRefGrades' => 'Разрешённые грейды',
			'grades' => 'Грейды'
		];
	}

	/**
	 * {@inheritDoc}
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
				'attribute' => 'name',
				'value' => static function(self $model) {
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => self::colorStyleOptions()
					]);
				},
				'format' => 'raw'
			],
			[
				'label' => 'Тип должности',
				'attribute' => 'typesId',
				'format' => 'raw',
				'filterType' => ReferenceSelectWidget::class,
				'filter' => RefUserPositionTypes::mapData(),
				'filterInputOptions' => ['placeholder' => 'Фильтр по типу'],
				'filterWidgetOptions' => [
					'referenceClass' => RefUserPositionTypes::class,
					'pluginOptions' => [
						'allowClear' => true, 'multiple' => true
					]
				],
				'value' => static function(self $model) {
					return BadgeWidget::widget([
						'models' => $model->relRefUserPositionTypes,
						'attribute' => 'name',
						'unbadgedCount' => 10,
						'itemsSeparator' => false,
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefUserPositionTypes'],
						"optionsMap" => RefUserPositionTypes::colorStyleOptions()
					]);
				}
			],
			[
				'attribute' => 'branchId',
				'format' => 'raw',
				'filterType' => ReferenceSelectWidget::class,
				'filterInputOptions' => ['placeholder' => 'Фильтр по ветви'],
				'filter' => RefUserPositionBranches::mapData(),
				'filterWidgetOptions' => [
					'referenceClass' => RefUserPositionBranches::class,
					'pluginOptions' => [
						'allowClear' => true, 'multiple' => true
					]
				],
				'value' => static function(self $model) {
					return BadgeWidget::widget([
						'models' => $model->relRefUserPositionBranch,
						'attribute' => 'name',
						'unbadgedCount' => 10,
						'itemsSeparator' => false,
						'linkScheme' => false,
						"optionsMap" => false
					]);
				}
			],
			[
				'label' => 'Грейды',
				'attribute' => 'gradesId',
				'format' => 'raw',
				'filterType' => ReferenceSelectWidget::class,
				'filterInputOptions' => ['placeholder' => 'Фильтр по грейду'],
				'filter' => RefGrades::mapData(),
				'filterWidgetOptions' => [
					'referenceClass' => RefGrades::class,
					'pluginOptions' => [
						'allowClear' => true, 'multiple' => true
					]
				],
				'value' => static function(self $model) {
					return BadgeWidget::widget([
						'models' => $model->relRefGrades,
						'attribute' => 'name',
						'unbadgedCount' => 10,
						'itemsSeparator' => false,
						'linkScheme' => [ReferencesModule::to('references/update'), 'id' => 'id', 'class' => 'RefGrades']
					]);
				}
			],
			[
				'attribute' => 'usedCount',
				'filter' => false,
				'value' => static function($model) {
					/** @var self $model */
					return BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'usedCount',
						'linkScheme' => [UsersModule::to(['users/index']), 'UsersSearch[positions][]' => $model->id],
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
	 * {@inheritDoc}
	 */
	public function search(array $params):ActiveQuery {
		/** @var ActiveQuery $query */
		$query = self::find();
		$this->load($params);
		$query->joinWith(['relRefUserPositionBranch', 'relRefUserPositionsBranches', 'relRefUserPositionTypes', 'relRefUserPositionsTypes', 'relRefGrades']);
		$query->andFilterWhere(['LIKE', 'name', $this->name]);
		$query->andFilterWhere(['rel_ref_user_positions_branches.position_branch_id' => $this->branchId]);//мог ошибиться с подстановкой верного поля, если будут косяки с фильтрацией - это так
		$query->andFilterWhere(['rel_ref_user_positions_types.position_type_id' => $this->typesId]);
		$query->andFilterWhere(['rel_grades_positions_rules.grade_id' => $this->gradesId]);

		return $query;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSearchSort():?array {
		return [
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'typesId' => [
					'asc' => ['ref_user_position_types.name' => SORT_ASC],
					'desc' => ['ref_user_position_types.name' => SORT_DESC]
				],
				'branchId' => [
					'asc' => ['ref_user_position_branches.name' => SORT_ASC],
					'desc' => ['ref_user_position_branches.name' => SORT_DESC]
				],
				'gradesId' => [
					'asc' => ['ref_salary_grades.name' => SORT_ASC],
					'desc' => ['ref_salary_grades.name' => SORT_DESC]
				]
			]
		];
	}

	/**
	 * Возвращает набор данных для выбора зарплатной вилки: там нужно разбитие по наличию грейдов
	 * @return array
	 */
	public static function mapByGrade():array {
		return ['Грейды заданы' => ArrayHelper::map(self::find()->joinWith('relRefGrades')->where(['not', ['ref_salary_grades.id' => null]])->active()->all(), "id", "name"),
			'Грейды не заданы' => ArrayHelper::map(self::find()->joinWith('relRefGrades')->where(['ref_salary_grades.id' => null])->active()->all(), 'id', 'name')
		];
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws Throwable
	 */
	public static function merge(int $fromId, int $toId):void {
		Users::updateAll(['position' => $toId], ['position' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)Users::find()->where(['position' => $this->id])->count();
	}

	/**
	 * @return RelRefUserPositionsBranches|ActiveQuery
	 */
	public function getRelRefUserPositionsBranches() {
		return $this->hasOne(RelRefUserPositionsBranches::class, ['position_id' => 'id']);
	}

	/**
	 * @return RefUserPositionBranches|ActiveQuery
	 */
	public function getRelRefUserPositionBranch() {
		return $this->hasOne(RefUserPositionBranches::class, ['id' => 'position_branch_id'])->via('relRefUserPositionsBranches');
	}

	/**
	 * @return int|null
	 * @throws Throwable
	 */
	public function getBranch():?int {
		return ArrayHelper::getValue($this->relRefUserPositionBranch, 'id');
	}

	/**
	 * @param mixed $branch
	 * //не можем типизировать null, т.к. может быть передана строка, а делать преобразования бессмысленно
	 * @throws Throwable
	 */
	public function setBranch($branch):void {
		RelRefUserPositionsBranches::deleteAll(['position_id' => $this->id]);
		RelRefUserPositionsBranches::linkModel($this->id, $branch);//проверки на пустоту делает метод
	}

	/**
	 * @return int[]
	 */
	public function getTypes():array {
		return ArrayHelper::getColumn($this->relRefUserPositionTypes, 'id');
	}

	/**
	 * @param mixed $types
	 * @throws Throwable
	 */
	public function setTypes($types):void {
		RelRefUserPositionsTypes::deleteAll(['position_id' => $this->id]);
		RelRefUserPositionsTypes::linkModels($this->id, $types);//проверки на пустоту делает метод
	}

	/**
	 * @return RelRefUserPositionsTypes[]|ActiveQuery
	 */
	public function getRelRefUserPositionsTypes() {
		return $this->hasMany(RelRefUserPositionsTypes::class, ['position_id' => 'id']);
	}

	/**
	 * @return RefUserPositionTypes[]|ActiveQuery
	 */
	public function getRelRefUserPositionTypes() {
		return $this->hasMany(RefUserPositionTypes::class, ['id' => 'position_type_id'])->via('relRefUserPositionsTypes');
	}

	/**
	 * @return RefGrades[]|ActiveQuery
	 */
	public function getRelRefGrades() {
		return $this->hasMany(RefGrades::class, ['id' => 'grade_id'])->via('relGradesPositionsRules');
	}

	/**
	 * @return RelGradesPositionsRules[]|ActiveQuery
	 */
	public function getRelGradesPositionsRules() {
		return $this->hasMany(RelGradesPositionsRules::class, ['position_id' => 'id']);
	}

	/**
	 * @param mixed $relGrades
	 * @throws Throwable
	 */
	public function setRelRefGrades($relGrades):void {
		RelGradesPositionsRules::deleteAll(['position_id' => $this->id]);
		RelGradesPositionsRules::linkModels($relGrades, $this->id);
	}

}
