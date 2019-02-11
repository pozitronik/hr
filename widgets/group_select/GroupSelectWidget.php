<?php
declare(strict_types = 1);

namespace app\widgets\group_select;

use app\helpers\ArrayHelper;
use app\models\core\SelectionWidget;
use app\modules\groups\models\Groups;
use app\models\references\refs\RefGroupTypes;
use kartik\base\InputWidget;
use yii\db\ActiveRecord;

/**
 * Виджет выбора группы (общий, для тех моделей, которые имеют нужные атрибуты).
 * Может работать в двух режимах. MODE_FIELD - как поле ActiveForm. В этом случае виджет является просто выбиралкой.
 * MODE_FORM- самостоятельная форма, в этом случае виджет сам отрендерит форму с указанным экшоном.
 *
 * Class GroupSelectWidget
 * @package app\components\group_select
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Группы, исключённые из списка (например те, в которых пользователь уже есть)
 * @property bool $groupByType Группировка списка по типам групп (двухуровневый список)
 * @property string $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 * @property boolean $multiple
 */
class GroupSelectWidget extends InputWidget implements SelectionWidget {
	public $mode = self::MODE_FIELD;
	public $notData;
	public $multiple = false;
	public $groupByType = true;
	public $formAction = '';

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

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('group_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'options' => Groups::dataOptions()
				]);
			break;
			case self::MODE_FORM:
				return $this->render('group_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'options' => Groups::dataOptions(),
					'formAction' => $this->formAction
				]);
			break;
		}

	}
}
