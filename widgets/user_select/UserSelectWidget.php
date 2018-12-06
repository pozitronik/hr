<?php
declare(strict_types = 1);

namespace app\widgets\user_select;

use app\helpers\ArrayHelper;
use app\models\users\Users;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class UserSelectWidget
 * Виджет списка пользователей (для добавления в группу)
 * @package app\components\user_select
 *
 * fixme: вероятно, некорректно работает отображение двух выбиралок на одной странице (коллизия по id)?
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Пользователи, исключённые из списка (например те, которые уже есть в группе)
 * @property boolean $multiple
 */
class UserSelectWidget extends Widget {
	public $model;
	public $attribute;
	public $notData;
	public $multiple = false;

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
		$data = Users::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])
			->all();

		return $this->render('user_select', [
			'model' => $this->model,
			'attribute' => $this->attribute,
			'data' => ArrayHelper::map($data, 'id', 'username'),
			'multiple' => $this->multiple
		]);
	}
}
