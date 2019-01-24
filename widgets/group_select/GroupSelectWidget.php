<?php
declare(strict_types = 1);

namespace app\widgets\group_select;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Возможно, стоит переписать в более общий вид, не только для групп
 * Class GroupSelectWidget
 * Виджет списка групп (для добавления пользователя)
 * @package app\components\group_select
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Группы, исключённые из списка (например те, в которых пользователь уже есть)
 * @property bool $groupByType Группировка списка по типу группы
 * @property boolean $multiple
 */
class GroupSelectWidget extends Widget {
	public $model;
	public $attribute;
	public $notData;
	public $multiple = false;
	public $groupByType = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$data = [];
		if ($this->groupByType) {
			foreach (RefGroupTypes::find()->active()->all() as $groupType) {
				$data[$groupType->name] = ArrayHelper::map($groups = Groups::find()->active()->where(['type' => $groupType->id])->andWhere(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
			}
			$data['Тип не указан'] = ArrayHelper::map(Groups::find()->active()->where(['type' => null])->andWhere(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
		} else {
			$data = ArrayHelper::map(Groups::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
		}

		return $this->render('group_select', [
			'model' => $this->model,
			'attribute' => $this->attribute,
			'data' => $data,
			'multiple' => $this->multiple,
			'options' => Groups::dataOptions()
		]);
	}
}
