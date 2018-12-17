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
 * @property boolean $useBadges
 * @property string|false $allBadgeClass
 * @property string $linkAttribute
 * @property array|false $linkScheme
 * @property string $itemsSeparator
 * @property integer|false $unbadgedCount
 * @property array $optionsMap
 * @property array $badgeOptions
 * @property array $moreBadgeOptions
 */
class BadgeWidget extends Widget {
	public $data = [];//Массив отображаемых моделей
	public $attribute;//Атрибут модели, отображаемый в текст
	public $unbadgedCount = 2;//Количество объектов, не сворачиваемых в бейдж
	public $useBadges = true;//использовать бейджи для основного списка.

	public $linkAttribute = 'id';//Атрибут, подставляемый в ссылку по схеме в $linkScheme. Строка, или массив строк (в этом случае подстановка идёт по порядку).
	public $linkScheme = false;//Url-схема, например ['/admin/groups/update', 'id' => 'id'] (Значение id будет взято из аттрибута id текущей модели), если false - то не используем ссылки
	public $itemsSeparator = ', ';//Разделитель объектов
	public $optionsMap = []; //Массив HTML-опций для каждого бейджа ([id => options])"
	public $badgeOptions = ['class' => 'badge'];//дефолтная опция для бейджа
	public $moreBadgeOptions = ['class' => 'badge pull-right'];//Массив HTML-опций для бейджа "ещё".

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
		$moreBadge = '';
		/** @var Model $model */
		foreach ($this->data as $model) {
			/** @noinspection PhpUndefinedFieldInspection - checked via hasProperty */
			$badgeHtmlOptions = $model->hasProperty('id')?ArrayHelper::getValue($this->optionsMap, $model->id, $this->badgeOptions):$this->badgeOptions;
			$badgeHtmlOptions = $badgeHtmlOptions??$this->badgeOptions;
			if ($this->linkScheme) {
				array_walk($this->linkScheme, function(&$value, $key) use ($model) {//постановка в схему значений из модели
					if ($model->hasProperty($value) && false !== $attributeValue = ArrayHelper::getValue($model, $value, false)) $value = $attributeValue;
				});
				$badgeContent = Html::a(ArrayHelper::getValue($model, $this->attribute), $this->linkScheme);
			} else {
				$badgeContent = ArrayHelper::getValue($model, $this->attribute);
			}

			if ($this->useBadges) {
				$result[] = Html::tag("span", $badgeContent, array_merge(['class' => 'badge'], $badgeHtmlOptions));
			} else {
				$result[] = $badgeContent;
			}

		}
		if ($this->unbadgedCount && count($result) > $this->unbadgedCount) {
			$moreBadge = Html::tag("span", "...ещё ".(count($result) - $this->unbadgedCount), $this->moreBadgeOptions);
			array_splice($result, $this->unbadgedCount, count($result));
		}
		return implode($this->itemsSeparator, $result).$moreBadge;

	}
}
