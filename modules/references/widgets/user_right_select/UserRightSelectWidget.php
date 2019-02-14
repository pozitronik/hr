<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\user_right_select;

use app\helpers\ArrayHelper;
use app\models\user_rights\Privileges;
use ReflectionException;
use yii\base\UnknownClassException;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class UserRightSelectWidget
 * Виджет списка групп (для добавления пользователя)
 * @package app\components\user_right_select
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Группы, исключённые из списка (например те, в которых пользователь уже есть)
 * @property boolean $multiple
 */
class UserRightSelectWidget extends Widget {
	public $model;
	public $attribute;
	public $notData;
	public $multiple = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserRightSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function run():string {

		$data = Privileges::GetRightsList(Privileges::RIGHTS_DIRECTORY, $this->notData);

		return $this->render('user_right_select', [
			'model' => $this->model,
			'attribute' => $this->attribute,
			'data' => ArrayHelper::map($data, 'id', 'name'),
			'multiple' => $this->multiple,
			'options' => Privileges::dataOptions()
		]);
	}
}
