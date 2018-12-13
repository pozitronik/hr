<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes;

use app\helpers\ArrayHelper;
use app\models\core\SysExceptions;
use app\models\users\Users;
use Throwable;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property DynamicAttributesSearchItem[] $searchItems
 *
 * @property $removeCondition
 * @property $addCondition
 */
class DynamicAttributesSearchCollection extends Model {
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
		if ([] === $this->searchItems) $this->searchItems[] = new DynamicAttributesSearchItem();//По умолчанию одно условие
	}

	/**
	 * @param DynamicAttributesSearchItem|null $condition
	 */
	public function addItem(DynamicAttributesSearchItem $condition = null):void {
		if (null === $condition) $condition = new DynamicAttributesSearchItem();
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
	 * @return DynamicAttributesSearchItem[]
	 */
	public function getSearchItems():array {
		return $this->searchItems;
	}

	/**
	 * @param DynamicAttributesSearchItem[] $searchItems
	 */
	public function setSearchItems(array $searchItems):void {
		$this->searchItems = [];
		foreach ($searchItems as $index => $search) {
			$this->searchItems[] = new DynamicAttributesSearchItem($search);
		}
	}

	/**
	 * Для предвыбранного атрибута нужно отдать его поля
	 * @param null|integer $index
	 * @return array
	 * @throws Throwable
	 */
	public function attributeProperties(?int $index):array {
		if (false !== $attribute = DynamicAttributes::findModel($index)) {
			return ArrayHelper::map($attribute->structure, 'id', 'name');
		}
		return [];
	}

	/**
	 * Для предвыбранного свойства нужно отдать его условия
	 * @param null|integer $attributeIndex
	 * @param null|integer $propertyIndex
	 * @return array
	 * @throws Throwable
	 */
	public function propertiesConditions(?int $attributeIndex, ?int $propertyIndex):array {
		if ((false !== $attribute = DynamicAttributes::findModel($attributeIndex)) && null !== $property = ArrayHelper::getValue($attribute->structure, $propertyIndex)) {
			$className = DynamicAttributeProperty::getTypeClass($type = $property['type']);
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
	public function propertyTypes(?int $index):array {
		$result = [];
		if (false !== $attribute = DynamicAttributes::findModel($index)) {
			foreach ($attribute->structure as $key => $value) {
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
			if (false === $model = DynamicAttributes::findModel($searchItem->attribute)) continue;
			$aliasName = "attributes{$searchItem->attribute}";
			if (!in_array($aliasName, $usedAliases)) {
				$query->leftJoin("rel_users_attributes $aliasName", "$aliasName.user_id = sys_users.id");
				$usedAliases[] = $aliasName;
			}

			$query->andFilterWhere(["$aliasName.attribute_id" => $searchItem->attribute]);
			if (null !== $type = ArrayHelper::getValue($model, "structure.{$searchItem->property}.type")) {
				$className = DynamicAttributeProperty::getTypeClass($type);
				if (null !== $condition = ArrayHelper::getValue($className::conditionConfig(), "{$searchItem->condition}.1")) {
					try {
						$typeAlias = $aliasName.$type;
						if (!in_array($typeAlias, $usedAliases)) {
							$typeTableName = $className::tableName();

							$query->leftJoin("$typeTableName $typeAlias", "$typeAlias.user_id = sys_users.id AND $typeAlias.property_id = {$searchItem->property}");
							$usedAliases[] = $typeAlias;
						}

						if ($searchItem->logic) {
							$query->andWhere($condition($typeAlias, $searchItem->value));
						} else {
							$query->orWhere($condition($typeAlias, $searchItem->value));
						}

					} catch (Throwable $t) {
						SysExceptions::log($t, true);
					}

				}
			}

		}
		//\Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}
}
