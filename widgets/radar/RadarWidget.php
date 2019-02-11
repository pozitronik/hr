<?php
declare(strict_types = 1);

namespace app\widgets\radar;

use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\users\models\Users;
use Exception;
use Throwable;
use yii\base\Widget;

/**
 * Class RadarWidget
 * Рисует радарную схему
 * @package app\widgets\radar
 * @property Users $user
 * @property DynamicAttributes $attribute
 * @property array|null $reference
 */
class RadarWidget extends Widget {
	public $user;
	public $attribute;
	public $reference;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		RadarWidgetAssets::register($this->getView());
	}

	/**
	 * Преобразует данные атрибута в набор данных для радарного графа
	 * @return array
	 * @throws Exception
	 * @throws Throwable
	 */
	private function GetGraphMap():array {
		$labels = [];
		$data = [];
		$properties = $this->attribute->getUserProperties($this->user->id);
		foreach ($properties as $property) {
			if ('percent' === $property->type) {
				$labels[] = $property->name;
				$data[] = $property->getValue();
				$this->reference[] = random_int(10, 90);
			}
		}
		return [
			'labels' => $labels,
			'datasets' => [
				[
					'label' => $this->attribute->name,
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
	 * @throws Exception
	 * @throws Throwable
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
