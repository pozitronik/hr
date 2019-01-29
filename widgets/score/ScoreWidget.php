<?php
declare(strict_types = 1);

namespace app\widgets\score;

use app\models\dynamic_attributes\types\ScoreProperty;
use yii\base\Widget;

/**
 * Class ScoreWidget
 * @package app\widgets\score
 * @property ScoreProperty $score
 * @property string $caption
 * @property bool $readOnly
 * @property bool $showEmpty
 */
class ScoreWidget extends Widget {
	public $caption;
	public $score;
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
			'caption' => $this->caption,
			'model' => $this->score,
			'showEmpty' => $this->showEmpty
		]):$this->render('score_edit', [
			'model' => $this->score,
			'caption' => $this->caption
		]);
	}
}
