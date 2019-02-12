<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user_select;

use app\helpers\ArrayHelper;
use app\models\core\SelectionWidgetInterface;
use app\modules\users\models\Users;
use kartik\base\InputWidget;
use yii\db\ActiveRecord;

/**
 * Class UserSelectWidget
 * Виджет списка пользователей (для добавления в группу)
 *
 * fixme: вероятно, некорректно работает отображение двух выбиралок на одной странице (коллизия по id)?
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Группы, исключённые из списка (например те, в которых пользователь уже есть)
 * @property bool $groupByType Группировка списка по типам групп (двухуровневый список)
 * @property string $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 * @property boolean $multiple
 * @property int $mode
 */
class UserSelectWidget extends InputWidget implements SelectionWidgetInterface {
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
		UserSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$data = ArrayHelper::map(Users::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])
			->all(), 'id', 'username');

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('user_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'options' => []
				]);
			break;
			case self::MODE_FORM:
				return $this->render('user_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'formAction' => $this->formAction,
					'options' => []
				]);
			break;
		}

	}
}
