<?php
declare(strict_types = 1);

namespace app\widgets\badge;

use app\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class BadgeWidget
 * @package app\widgets\badge
 * @property array<Model> $data
 * @property string $attribute
 * @property integer|false $unbadgedCount
 * @property string $badgeClass
 * @property bool $itemsAsLinks
 * @property string $linkAttribute
 * @property array $linkScheme
 * @property string $itemsSeparator
 */
class BadgeWidget extends Widget {
	public $data = [];//Массив отображаемых моделей
	public $attribute;//Атрибут модели, отображаемый в текст
	public $unbadgedCount = 2;//Количество объектов, не сворачиваемых в бейдж
	public $badgeClass = '';//дополнительный класс бейджа
	public $itemsAsLinks = true;//преобразоввывать подписи в ссылки
	public $linkAttribute = 'id';//Атрибут, подставляемый в ссылку по схеме в $linkScheme. Строка, или массив строк (в этом случае подстановка идёт по порядку).
	public $linkScheme = [];//Url-схема, например ['/admin/groups/update', 'id' => 'id'] (Значение id будет взято из аттрибута id текущей модели)
	public $itemsSeparator = ', ';//Разделитель объектов

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		BadgeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$result = [];
		/** @var Model $model */
		foreach ($this->data as $model) {
			if ($this->itemsAsLinks) {
				$linkScheme = $this->linkScheme;
				foreach ($linkScheme as $key => &$value) {//постановка в схему значений из модели
					if ($model->hasProperty($value) && false !== $attributeValue = ArrayHelper::getValue($model, $value, false)) $value = $attributeValue;
				}
				unset($value);
				$result[] = Html::a(ArrayHelper::getValue($model, $this->attribute), $linkScheme);
			} else {
				$result[] = ArrayHelper::getValue($model, $this->attribute);
			}
		}
		if ($this->unbadgedCount && count($result) > $this->unbadgedCount) {
			$badge = "<b class='badge {$this->badgeClass}'>...ещё ".(count($result) - $this->unbadgedCount)."</b>";
			array_splice($result, $this->unbadgedCount, count($result));
			return implode($this->itemsSeparator, $result).$badge;
		}
		return implode($this->itemsSeparator, $result);

	}
}
