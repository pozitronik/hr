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
 * @property array|null $reference
 */
class RadarWidget extends Widget {
	public $user;
	public $competency;
	public $reference;

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
				$this->reference[] = random_int(10, 90);
			}
		}
		return [
			'labels' => $labels,
			'datasets' => [
				[
					'label' => $this->competency->name,
					'data' => $data
				],
				[
					'label' => 'Референс (сейчас он случайный)',
					'backgroundColor' => 'rgba(0, 200, 20, 0.1)',
					'data' => $this->reference
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
		if ([] === $data['labels']) {
			return $this->render('empty');
		}

		return $this->render('radar',[
			'data' => $data
		]);
	}
}
