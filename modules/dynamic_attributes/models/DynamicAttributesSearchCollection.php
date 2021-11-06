<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use app\components\pozitronik\sys_exceptions\SysExceptions;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\groups\models\Groups;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use Throwable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * @property DynamicAttributesSearchItem[] $searchItems
 * @property int[] $searchScope
 * @property bool $searchTree
 */
class DynamicAttributesSearchCollection extends Model {
	private $searchItems = [];
	private $searchScope = [0];//Область поиска. 0 - все группы, -1 - все мои группы, -2 - все группы, где я босс. Иначе выбранная группа
	private $searchTree = true;//true - искать по всему дереву, false - только в выбранной группе

	public const SCOPE_ALL_GROUPS = 0;
	public const SCOPE_MY_GROUPS = -1;
	public const SCOPE_BOSS_GROUPS = -2;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['searchItems'], 'safe'],
			[['searchScope'], 'safe'],
			[['searchTree'], 'boolean']
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

	/**
	 *
	 */
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
	 * @param null|int $index
	 * @return array
	 * @throws Throwable
	 */
	public function attributeProperties(?int $index):array {
		if (null !== $attribute = DynamicAttributes::findModel($index)) {
			return ArrayHelper::map($attribute->structure, 'id', 'name');
		}
		return [];
	}

	/**
	 * Для предвыбранного свойства нужно отдать его условия
	 * @param null|int $attributeIndex
	 * @param null|int $propertyIndex
	 * @return array
	 * @throws Throwable
	 */
	public function propertiesConditions(?int $attributeIndex, ?int $propertyIndex):array {
		if ((null !== $attribute = DynamicAttributes::findModel($attributeIndex)) && null !== $property = ArrayHelper::getValue($attribute->structure, $propertyIndex)) {
			$className = DynamicAttributeProperty::getTypeClass($property['type']);
			return ArrayHelper::keymap($className::conditionConfig(), 0);
		}
		return [];
	}

	/**
	 * Для задания data-type-атрибутов у выбиралки типов при поиске придумано вот такое решение
	 * @see https://github.com/kartik-v/yii2-widgets/issues/247
	 * @param null|int $index
	 * @return array
	 * @throws Throwable
	 */
	public function propertyTypes(?int $index):array {
		$result = [];
		if (null !== $attribute = DynamicAttributes::findModel($index)) {
			foreach ($attribute->structure as $key => $value) {
				$result[$key]['data-type'] = ArrayHelper::getValue($value, 'type');
			}

		}
		return $result;
	}

	/**
	 * Значения поисковой выбиралки
	 * @return array
	 */
	public static function searchGroups():array {
		return [
				self::SCOPE_ALL_GROUPS => 'Все группы',
				self::SCOPE_MY_GROUPS => 'Группы, в которых я состою',
				self::SCOPE_BOSS_GROUPS => 'Группы под моим руководством'
			] + ArrayHelper::map(Groups::find()->active()->all(), 'id', 'name');
	}

	/**
	 * @param ActiveQuery $query
	 * @return ActiveQuery
	 * @throws Throwable
	 */
	private function applySearchScope(ActiveQuery $query):ActiveQuery {
		$groups = [[]];
		if (null === $user = CurrentUser::User()) return $query;
		foreach ($this->searchScope as $groupId) {
			switch ($groupId) {
				case self::SCOPE_ALL_GROUPS://все группы - это все группы
					return $query;
				default:
					$groups[] = Groups::findModels($this->searchScope);
				break;
				case self::SCOPE_MY_GROUPS:
					$groups[] = $user->relGroups;
				break;
				case self::SCOPE_BOSS_GROUPS:
					$groups[] = $user->relLeadingGroups;
				break;
			}
		}
		$groups = array_merge(...$groups);

		$query->joinWith(['relGroups']);
		if ($this->searchTree) {
			$t = [[]];
			foreach ($groups as $group) {
				$t[] = $group->collectRecursiveIds();
			}
			$ids = array_unique(array_merge(...$t));
			$query->andWhere(['sys_groups.id' => $ids]);
		} else {
			$query->andWhere(['sys_groups.id' => ArrayHelper::getColumn($groups, 'id')]);
		}

		return $query;
	}

	/**
	 * @return ActiveDataProvider
	 * @throws Throwable
	 */
	public function searchCondition():ActiveDataProvider {
		$query = Users::find()->distinct()->active()->joinWith(['relUsersAttributesTypes']);
		$query = $this->applySearchScope($query);

		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);

		$dataProvider->setSort([
			'defaultOrder' => ['username' => SORT_ASC],
			'attributes' => [
				'id',
				'username'
			]
		]);
		$usedAliases = [];//Массив-счётчик использованных алиасов. Если имя алиаса было использовано, второй раз ссылаться на него не нужно

		foreach ($this->searchItems as $searchItem) {
			if (null === $model = DynamicAttributes::findModel($searchItem->attribute)) continue;
			$aliasName = "attributes{$searchItem->attribute}";
			if (!in_array($aliasName, $usedAliases)) {
				$query->leftJoin("rel_users_attributes $aliasName", "$aliasName.user_id = sys_users.id");
				$usedAliases[] = $aliasName;
			}
			if (null !== $type = ArrayHelper::getValue($model, "structure.{$searchItem->property}.type")) {
				$className = DynamicAttributeProperty::getTypeClass($type);
				if (null !== $condition = ArrayHelper::getValue($className::conditionConfig(), "{$searchItem->condition}.1")) {
					/** @noinspection BadExceptionsProcessingInspection */
					try {
						$typeAlias = $aliasName.$type.$searchItem->property;
						if (!in_array($typeAlias, $usedAliases)) {
							$typeTableName = $className::tableName();

							$query->leftJoin("$typeTableName $typeAlias", "$typeAlias.user_id = sys_users.id AND $typeAlias.property_id = {$searchItem->property} AND $typeAlias.attribute_id = $aliasName.attribute_id");
							$usedAliases[] = $typeAlias;
						}
						$conditionResult = $condition($typeAlias, $searchItem->value);
						$query->andFilterWhere(['rel_users_attributes_types.type' => $searchItem->type]);

						if ($searchItem->union) {
							$query->andWhere($conditionResult);
						} else {
							$query->orWhere($conditionResult);
						}

					} catch (Throwable $t) {
						SysExceptions::log($t, true);
					}

				}
			}
			$query->andFilterWhere(["$aliasName.attribute_id" => $searchItem->attribute]);
		}
		//\Yii::debug($query->createCommand()->rawSql, 'sql');
		return $dataProvider;
	}

	/**
	 * @param bool $searchTree
	 */
	public function setSearchTree(bool $searchTree):void {
		$this->searchTree = $searchTree;
	}

	/**
	 * @param int[] $searchScope
	 */
	public function setSearchScope(array $searchScope):void {
		$this->searchScope = empty($searchScope)?[]:$searchScope;
	}

	/**
	 * @return int[]
	 */
	public function getSearchScope():array {
		return $this->searchScope;
	}

	/**
	 * @return bool
	 */
	public function getSearchTree():bool {
		return $this->searchTree;
	}

}
