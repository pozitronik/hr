<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field_score;

use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\widgets\InputWidget;

/**
 * Class ScoreWidget
 * @property DynamicAttributeProperty $model
 * @property string $attribute
 * @property bool $readOnly
 * @property bool $showEmpty
 */
class ScoreWidget extends InputWidget {
	public $readOnly = false;
	public $showEmpty = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ScoreWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * Виджет не может возвращать null
	 * @return string
	 */
	public function run():string {
		if ($this->readOnly && !$this->showEmpty && $this->model->{$this->attribute}->empty) return '';

		return $this->readOnly?$this->render('score_view', [
			'attribute' => $this->attribute,
			'model' => $this->model
		]):$this->render('score_edit', [
			'attribute' => $this->attribute,
			'model' => $this->model
		]);
	}
}
