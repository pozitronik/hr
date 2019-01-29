<?php
declare(strict_types = 1);

namespace app\widgets\score;

use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\base\Widget;

/**
 * Class ScoreWidget
 * @package app\widgets\score
 * @property DynamicAttributeProperty$model
 * @property string $attribute
 * @property bool $readOnly
 * @property bool $showEmpty
 */
class ScoreWidget extends Widget {
	public $model;
	public $attribute = 'value';
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
	 * @return string
	 */
	public function run():string {
		return $this->readOnly?$this->render('score_view', [
			'caption' => $this->model->name,
			'model' => $this->model->{$this->attribute},
			'showEmpty' => $this->showEmpty
		]):$this->render('score_edit', [
			'caption' => $this->model->name,
			'model' => $this->model->{$this->attribute}
		]);
	}
}
