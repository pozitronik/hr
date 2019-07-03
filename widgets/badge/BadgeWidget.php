<?php
declare(strict_types = 1);

namespace app\widgets\badge;

use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class BadgeWidget
 * @package app\widgets\badge
 * @property string|array|object $models
 * @property string $attribute
 * @property boolean $useBadges
 * @property string|false $allBadgeClass
 * @property string $linkAttribute
 * @property array|false $linkScheme
 * @property string $itemsSeparator
 * @property integer|false $unbadgedCount
 * @property array|callable $optionsMap
 * @property array $badgeOptions
 * @property array $moreBadgeOptions
 *
 */
class BadgeWidget extends Widget {
	public $models;//Обрабатываемое значение/массив значений. Допускаются любые комбинации
	public $attribute;//Атрибут модели, отображаемый в текст
//	public $value;//непосредственное значение, для использования без модели
	public $unbadgedCount = 2;//Количество объектов, не сворачиваемых в бейдж
	public $useBadges = true;//использовать бейджи для основного списка.

	public $linkAttribute = 'id';//Атрибут, подставляемый в ссылку по схеме в $linkScheme. Строка, или массив строк (в этом случае подстановка идёт по порядку).
	public $linkScheme = false;//Url-схема, например ['/groups/groups/profile', 'id' => 'id'] (Значение id будет взято из аттрибута id текущей модели), если false - то не используем ссылки
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

		if (null === $this->models) throw new InvalidConfigException('Model property not properly configured');

		if (!is_array($this->models)) $this->models = [$this->models];

		if (is_callable($this->optionsMap)) $this->optionsMap = call_user_func($this->optionsMap);
		/** @var Model|ActiveRecord $model */

		foreach ($this->models as $model) {
			if (null === $model) continue;

			if (!is_object($model)) {
				if (is_array($model)) {
					$model = new DynamicModel($model);
				} else {
					$model = new DynamicModel(['value' => $model]);
					$this->attribute = 'value';
				}
			}

			if ($model->hasProperty('primaryKey')) {
				$badgeHtmlOptions = (null === $model->primaryKey)?$this->badgeOptions:ArrayHelper::getValue($this->optionsMap, $model->primaryKey, $this->badgeOptions);
			} else {
				/** @noinspection PhpUndefinedFieldInspection */
				$badgeHtmlOptions = $model->hasProperty('id')?ArrayHelper::getValue($this->optionsMap, $model->id, $this->badgeOptions):$this->badgeOptions;
			}

			if (!is_array($badgeHtmlOptions)) $badgeHtmlOptions = $this->badgeOptions;
			if ($this->linkScheme) {
				$currentLinkScheme = $this->linkScheme;
				array_walk($currentLinkScheme, static function(&$value, $key) use ($model) {//подстановка в схему значений из модели
					if ($model->hasProperty($value) && false !== $attributeValue = ArrayHelper::getValue($model, $value, false)) $value = $attributeValue;
				});
				$badgeContent = Html::a(ArrayHelper::getValue($model, $this->attribute), $currentLinkScheme);
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
