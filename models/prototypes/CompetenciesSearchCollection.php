<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use app\models\core\SysExceptions;
use app\models\users\Users;
use Throwable;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class PrototypeCompetenciesSearchSet
 * @package app\models\prototypes
 *
 * @property CompetenciesSearchItem[] $searchItems
 *
 * @property $removeCondition
 * @property $addCondition
 */
class CompetenciesSearchCollection extends Model {
	private $searchItems = [];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['searchItems'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'searchItems' => 'Набор условий'
		];
	}

	public function init():void {
		parent::init();
		if ([] === $this->searchItems) $this->searchItems[] = new CompetenciesSearchItem();//По умолчанию одно условие
	}

	/**
	 * @param CompetenciesSearchItem|null $condition
	 */
	public function addItem(CompetenciesSearchItem $condition = null):void {
		if (null === $condition) $condition = new CompetenciesSearchItem();
		$this->searchItems[] = $condition;
	}

	/**
	 * @param null|int $index
	 */
	public function removeItem(?int $index = null):void {
		if (null === $index) {
			ArrayHelper::remove($this->searchItems, count($this->searchItems) - 1);
		} else {
			ArrayHelper::remove($this->searchItems, $index);
		}

	}

	/**
	 * @return CompetenciesSearchItem[]
	 */
	public function getSearchItems():array {
		return $this->searchItems;
	}

	/**
	 * @param CompetenciesSearchItem[] $searchItems
	 */
	public function setSearchItems(array $searchItems):void {
		$this->searchItems = [];
		foreach ($searchItems as $index => $search) {
			$this->searchItems[] = new CompetenciesSearchItem($search);
		}
	}

	/**
	 * Для предвыбранной компетенции нужно отдать её поля
	 * @param null|integer $index
	 * @return array
	 * @throws Throwable
	 */
	public function competencyFields(?int $index):array {
		if (false !== $competency = Competencies::findModel($index)) {
			return ArrayHelper::map($competency->structure, 'id', 'name');
		}
		return [];
	}

	/**
	 * Для предвыбранной компетенции нужно отдать её поля
	 * @param null|integer $competencyIndex
	 * @param null|integer $fieldIndex
	 * @return array
	 * @throws Throwable
	 */
	public function fieldsConditions(?int $competencyIndex, ?int $fieldIndex):array {
		if ((false !== $competency = Competencies::findModel($competencyIndex)) && null !== $field = ArrayHelper::getValue($competency->structure, $fieldIndex)) {
			$className = CompetencyField::getTypeClass($type = $field['type']);
			return ArrayHelper::keymap($className::conditionConfig(), 0);
		}
		return [];
	}

	/**
	 * Для задания data-type-атрибутов у выбиралки типов при поиске придумано вот такое решение
	 * @see https://github.com/kartik-v/yii2-widgets/issues/247
	 * @param null|integer $index
	 * @return array
	 * @throws Throwable
	 */
	public function fieldsTypes(?int $index):array {
		$result = [];
		if (false !== $competency = Competencies::findModel($index)) {
			foreach ($competency->structure as $key => $value) {
				$result[$key]['data-type'] = ArrayHelper::getValue($value, 'type');
			}

		}
		return $result;
	}

	/**
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function searchCondition():ActiveDataProvider {
		$query = Users::find()->active();

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['id' => SORT_ASC],
			'attributes' => [
				'id',
				'username'
			]
		]);
		$usedAliases = [];//Массив-счётчик использованных алиасов. Если имя алиаса было использовано, второй раз ссылаться на него не нужно

		foreach ($this->searchItems as $searchItem) {
			if (false === $model = Competencies::findModel($searchItem->competency)) continue;
			$aliasName = "competencies{$searchItem->competency}";
			if (!in_array($aliasName, $usedAliases)) {
				$query->leftJoin("rel_users_competencies $aliasName", "$aliasName.user_id = sys_users.id");
				$usedAliases[] = $aliasName;
			}

			$query->andFilterWhere(["$aliasName.competency_id" => $searchItem->competency]);
			if (null !== $type = ArrayHelper::getValue($model, "structure.{$searchItem->field}.type")) {
				$className = CompetencyField::getTypeClass($type);
				if (null !== $condition = ArrayHelper::getValue($className::conditionConfig(), "{$searchItem->condition}.1")) {
					try {
						$typeAlias = $aliasName.$type;
						if (!in_array($typeAlias, $usedAliases)) {
							$typeTableName = $className::tableName();

							$query->leftJoin("$typeTableName $typeAlias", "$typeAlias.user_id = sys_users.id");
							$usedAliases[] = $typeAlias;
						}

						if ($searchItem->logic) {
							$query->andFilterWhere($condition($typeAlias, $searchItem->value));
						} else {
							$query->orFilterWhere($condition($typeAlias, $searchItem->value));
						}

					} catch (Throwable $t) {
						SysExceptions::log($t, true);
					}

				}
			}

		}
		\Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}
}
