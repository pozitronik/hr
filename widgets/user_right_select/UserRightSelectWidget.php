<?php
declare(strict_types = 1);

namespace app\widgets\user_right_select;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * @todo не нужно в таком виде, стоит использовать не виджет, а прямое обращение
 * Возможно, стоит переписать в более общий вид, не только для групп
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
	 */
	public function run():string {
		$data = Groups::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])
			->all();

		return $this->render('user_right_select', [
			'model' => $this->model,
			'attribute' => $this->attribute,
			'data' => ArrayHelper::map($data, 'id', 'name'),
			'multiple' => $this->multiple
		]);
	}
}
