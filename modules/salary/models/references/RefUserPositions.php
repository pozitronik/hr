<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\helpers\ArrayHelper;
use app\modules\salary\models\relations\RelGradesPositionsRules;
use app\modules\references\models\Reference;
use app\modules\salary\models\relations\RelRefUserPositionsBranches;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\Users;
use app\widgets\badge\BadgeWidget;
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
 * @property RefGrades[]|ActiveQuery $relGrades Грейды, разрешённые для этой должности
 *
 * @property null|int $branch
 * @property null|int[] $types
 *
 * @property-read null|string $branchName
 */
class RefUserPositions extends Reference {
	public $menuCaption = 'Должности';
	public $menuIcon = false;

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
			[['id', 'deleted'], 'integer'],
			[['name', 'color'], 'string', 'max' => 256],
			[['branch', 'types', 'relGrades'], 'safe'],//relational attributes
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
			'types' => 'Типы',
			'relGrades' => 'Разрешённые грейды'
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
						]).$model->name:Html::tag('span', Html::a($model->name, ['update', 'class' => $model->formName(), 'id' => $model->id]), [
						'style' => "background: {$model->color}"
					]);
				},
				'format' => 'raw'
			],
			[
				'label' => 'Тип должности',
				'format' => 'raw',
				'value' => static function(self $model) {
					return BadgeWidget::widget([
						'data' => $model->relRefUserPositionTypes,
						'attribute' => 'name',
						'unbadgedCount' => 10,
						'itemsSeparator' => false,
						'linkScheme' => ['/references/references/update', 'id' => 'id', 'class' => 'RefUserPositionTypes'],
						"optionsMap" => static function() {
							$options = ArrayHelper::map(RefUserPositionTypes::find()->active()->all(), 'id', 'color');
							array_walk($options, static function(&$value, $key) {
								if (!empty($value)) {
									$value = [
										'style' => "background: $value;"
									];
								}
							});
							return $options;
						}
					]);
				}
			],

			[
				'attribute' => 'branchName'
			],
			[
				'label' => 'Грейды',
				'format' => 'raw',
				'value' => static function(self $model) {
					return BadgeWidget::widget([
						'data' => $model->relGrades,
						'attribute' => 'name',
						'unbadgedCount' => 10,
						'itemsSeparator' => false,
						'linkScheme' => ['/references/references/update', 'id' => 'id', 'class' => 'RefGrades']
					]);
				}
			],
			[
				'attribute' => 'usedCount'
			]

		];
	}

	/**
	 * Возвращает набор данных для выбора зарплатной вилки: там нужно разбитие по наличию грейдов
	 * @return array
	 */
	public static function mapByGrade():array {
		return [
			'Грейды заданы' => ArrayHelper::map(self::find()->joinWith('relGrades')->where(['not', ['ref_salary_grades.id' => null]])->active()->all(), "id", "name"),
			'Грейды не заданы' => ArrayHelper::map(self::find()->joinWith('relGrades')->where(['ref_salary_grades.id' => null])->active()->all(), 'id', 'name')
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
		self::deleteAllEx(['id' => $fromId]);
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
	 * @return null|string
	 * @throws Throwable
	 * @temporary
	 */
	public function getBranchName():?string {
		return ArrayHelper::getValue($this->relRefUserPositionBranch, 'name');
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
		RelRefUserPositionsBranches::deleteAllEx(['position_id' => $this->id]);
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
		RelRefUserPositionsTypes::deleteAllEx(['position_id' => $this->id]);
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
	public function getRelGrades() {
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
	public function setRelGrades($relGrades):void {
		RelGradesPositionsRules::deleteAllEx(['position_id' => $this->id]);
		RelGradesPositionsRules::linkModels($relGrades, $this->id);
	}

}
