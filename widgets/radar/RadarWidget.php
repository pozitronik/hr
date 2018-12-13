<?php
declare(strict_types = 1);

namespace app\widgets\radar;

use app\models\competencies\Competencies;
use app\models\users\Users;
use yii\base\Widget;

/**
 * Class RadarWidget
 * Рисует радарную схему
 * @package app\widgets\radar
 * @property Users $user
 * @property Competencies $competency
 */
class RadarWidget extends Widget {
	public $user;
	public $competency;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		RadarWidgetAssets::register($this->getView());
	}

	/**
	 * Преобразет данные компетенции в набор данных для радарного графа
	 * @return array
	 */
	private function GetGraphMap():array {
		$labels = [];
		$data = [];
		$fields = $this->competency->getUserFields($this->user->id);
		foreach ($fields as $field) {
			if ('percent' === $field->type) {
				$labels[] = $field->name;
				$data[] = $field->value;
			}
		}
		return [
			'labels' => $labels,
			'datasets' => [
				[
					'label' => $this->competency->name,
					'data' => $data
				]
			]
		];

	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$data = $this->GetGraphMap();

		return $this->render('radar',[
			'data' => $data
		]);
	}
}
