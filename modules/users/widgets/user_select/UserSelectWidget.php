<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user_select;

use pozitronik\helpers\ArrayHelper;
use app\models\core\SelectionWidgetInterface;
use app\modules\users\models\Users;
use kartik\base\InputWidget;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class UserSelectWidget
 * Виджет списка пользователей (для добавления в группу)
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Группы, исключённые из списка (например те, в которых пользователь уже есть)
 * @property bool $groupByType Группировка списка по типам групп (двухуровневый список)
 * @property string $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 * @property boolean $multiple
 * @property int $mode Режим рендеринга
 * @property int $dataMode Режим загрузки данных
 *
 * @todo: я плохо спроектировал эту выбиралку. Она привязана к моделям групп, и не является универсальной. Требуется полностью её переделать, соблюдая следующие принципы:
 * 1) Выбиралка должна учитывать ТОЛЬКО пользоватеелй. Ни о каких более моделях она не знает.
 * 2) Соответственно, вся фильтрация должна происходить уровнем выше.
 * 3) Аяксовая фильтрация, используемая соответствующим методом загрузки... да хрен с ней, добавление дубликатов фильтруется на сервере
 */
class UserSelectWidget extends InputWidget implements SelectionWidgetInterface {
	public $mode = self::MODE_FIELD;
	public $dataMode = self::DATA_MODE_LOAD;
	public $notData = [];
	public $multiple = false;
	public $groupByType = true;
	public $formAction = '';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserSelectWidgetAssets::register($this->getView());
		$this->options['id'] = isset($this->options['id'])?$this->options['id'].$this->model->primaryKey:Html::getInputId($this->model, $this->attribute).$this->model->primaryKey;
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$data = self::DATA_MODE_AJAX === $this->dataMode?[]:ArrayHelper::map(Users::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])
			->all(), 'id', 'username');

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('user_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'data_mode' => $this->dataMode,
					'multiple' => $this->multiple,
					'options' => $this->options,
					'ajax_search_url' => '/users/ajax/user-search'
				]);
			break;
			case self::MODE_FORM:
				return $this->render('user_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'data_mode' => $this->dataMode,
					'multiple' => $this->multiple,
					'formAction' => $this->formAction,
					'options' => $this->options,
					'ajax_search_url' => '/users/ajax/user-search'
				]);
			break;
			case self::MODE_AJAX:
				return $this->render('user_select_ajax', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'data_mode' => $this->dataMode,
					'multiple' => $this->multiple,
					'ajax_post_url' => '/users/ajax/users-add-to-group',
					'options' => $this->options,
					'ajax_search_url' => '/users/ajax/user-search'
				]);
			break;
		}

	}
}
