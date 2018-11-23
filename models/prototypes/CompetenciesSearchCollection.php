<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use Throwable;
use yii\base\Model;

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
	 * @param int $index
	 */
	public function removeItem(int $index):void {
		ArrayHelper::remove($this->searchItems, $index);
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
		if (false !== $competency = Competencies::findModel($competencyIndex)) {
			$field = $competency->structure[$fieldIndex];

			$type = $field['type'];
			return CompetencySearchCondition::findCondition($type);//todo: здесь поменяется, когда будет понятна структура SearchCondition
		}
		return [];
	}

}
