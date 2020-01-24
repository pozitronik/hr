<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\target_select;

use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use pozitronik\helpers\ArrayHelper;
use app\models\core\SelectionWidgetInterface;
use kartik\base\InputWidget;
use yii\db\ActiveRecord;

/**
 * Виджет выбора задачи целеполагания (общий, для тех моделей, которые имеют нужные атрибуты).
 * Может работать в двух режимах. MODE_FIELD - как поле ActiveForm. В этом случае виджет является просто выбиралкой.
 * MODE_FORM - самостоятельная форма, в этом случае виджет сам отрендерит форму с указанным экшоном.
 *
 * Class TargetSelectWidget
 * @package app\components\target_select
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Цели, исключённые из списка
 * @property bool $targetByType Группировка списка по типам целей (двухуровневый список)
 * @property string $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 * @property boolean $multiple
 * @property int $mode
 * @property int $dataMode Режим загрузки данных
 *
 * @todo: см. тудуху в UserSelectWidget, здесь те же ошибки
 */
class TargetSelectWidget extends InputWidget implements SelectionWidgetInterface {
	public $mode = self::MODE_FIELD;
	public $dataMode = self::DATA_MODE_LOAD;
	public $notData = [];
	public $multiple = false;
	public $targetByType = true;
	public $formAction = '';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		TargetSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$data = [];
		if (self::DATA_MODE_LOAD === $this->dataMode) {
			if ($this->targetByType) {
				foreach (RefTargetsTypes::find()->active()->all() as $targetType) {
					$data[$targetType->name] = ArrayHelper::map(Targets::find()->active()->where(['type' => $targetType->id])->andWhere(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
				}
				$data['Тип не указан'] = ArrayHelper::map(Targets::find()->active()->where(['type' => null])->andWhere(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
			} else {
				$data = ArrayHelper::map(Targets::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->all(), 'id', 'name');
			}
		}

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('target_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'data_mode' => $this->dataMode,
					'multiple' => $this->multiple,
					'options' => Targets::dataOptions(),
					'ajax_search_url' => '/targets/ajax/target-search'
				]);
			break;
			case self::MODE_FORM:
				return $this->render('target_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'data_mode' => $this->dataMode,
					'multiple' => $this->multiple,
					'options' => Targets::dataOptions(),
					'formAction' => $this->formAction,
					'ajax_search_url' => '/targets/ajax/target-search'
				]);
			break;
		}

	}
}
