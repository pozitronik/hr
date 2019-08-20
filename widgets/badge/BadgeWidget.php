<?php
declare(strict_types = 1);

namespace app\widgets\badge;

use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\ReflectionHelper;
use pozitronik\widgets\CachedWidget;
use Throwable;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class BadgeWidget
 * @package app\widgets\badge
 * @property string|array|object|callable $models
 * @property string $attribute
 * @property boolean $useBadges
 * @property string|false $allBadgeClass
 * @property string $linkAttribute
 * @property array|false $linkScheme
 * @property string $itemsSeparator
 * @property integer|false $unbadgedCount
 * @property array|callable $optionsMap
 * @property null|string $optionsMapAttribute
 * @property array $badgeOptions
 * @property array $moreBadgeOptions
 * @property string $prefix
 * @property string|null|false $emptyResult
 */
class BadgeWidget extends CachedWidget {
	public $models;//Обрабатываемое значение/массив значений. Допускаются любые комбинации
	public $attribute;//Атрибут модели, отображаемый в текст
	public $unbadgedCount = 2;//Количество объектов, не сворачиваемых в бейдж
	public $useBadges = true;//использовать бейджи для основного списка.

	public $linkAttribute = 'id';//Атрибут, подставляемый в ссылку по схеме в $linkScheme. Строка, или массив строк (в этом случае подстановка идёт по порядку).
	public $linkScheme = false;//Url-схема, например ['/groups/groups/profile', 'id' => 'id'] (Значение id будет взято из аттрибута id текущей модели), если false - то не используем ссылки
	public $itemsSeparator = ', ';//Разделитель объектов
	public $optionsMap = []; //Массив HTML-опций для каждого бейджа ([optionsMapAttributeValue => options])". Если установлен, мержится с $badgeOptions
	public $optionsMapAttribute; //Имя аттрибута, используемого для подбора значения в $optionsMap, если null, то используется primaryKey (или id, если модель не имеет первичного ключа)
	public $badgeOptions = ['class' => 'badge'];//дефолтная опция для бейджа
	public $moreBadgeOptions = ['class' => 'badge pull-right'];//Массив HTML-опций для бейджа "ещё".
	public $prefix = '';//строчка, добавляемая перед бейджами
	public $emptyResult = false;//значение, возвращаемое, если из обрабатываемых данных не удалось получить результат (обрабатываем пустые массивы, модель не содержит данных, etc)

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

//		if (null === $this->models) throw new InvalidConfigException('Model property not properly configured');
		if (ReflectionHelper::is_closure($this->models)) $this->models = call_user_func($this->models);

		if (!is_array($this->models)) $this->models = [$this->models];

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

			if (ReflectionHelper::is_closure($this->optionsMap)) $this->optionsMap = call_user_func($this->optionsMap, $model);

			if (null === $this->optionsMapAttribute && $model->hasProperty('primaryKey')) {
				$badgeHtmlOptions = (null === $model->primaryKey)?$this->badgeOptions:ArrayHelper::getValue($this->optionsMap, $model->primaryKey, $this->badgeOptions);
			} else {
				if (null === $this->optionsMapAttribute && $model->hasProperty('id')) $this->optionsMapAttribute = 'id';
				$badgeHtmlOptions = $model->hasProperty($this->optionsMapAttribute)?ArrayHelper::getValue($this->optionsMap, $model->{$this->optionsMapAttribute}, $this->badgeOptions):$this->badgeOptions;
			}

			$badgeHtmlOptions = !is_array($badgeHtmlOptions)?$this->badgeOptions:array_merge($this->badgeOptions, $badgeHtmlOptions);
			if ($this->linkScheme) {
				$currentLinkScheme = $this->linkScheme;
				$arrayedParameters = [];
				array_walk($currentLinkScheme, static function(&$value, $key) use ($model, &$arrayedParameters) {//подстановка в схему значений из модели
					if (is_array($value)) {//value passed as SomeParameter => [a,b,c,...] => convert to SomeParameter[1] => a, SomeParameter[2] => b, SomeParameter[3] => c
						foreach ($value as $index => $item) {
							$arrayedParameters["{$key}[{$index}]"] = $item;
						}
					} else if ($model->hasProperty($value) && false !== $attributeValue = ArrayHelper::getValue($model, $value, false)) $value = $attributeValue;

				});
				if ([] !== $arrayedParameters) array_merge($currentLinkScheme, $arrayedParameters);//если в схеме были переданы значения массивом, включаем их разбор в схему
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
		if ([] === $result && false !== $this->emptyResult) $result = [$this->emptyResult];
		return $this->prefix.implode($this->itemsSeparator, $result).$moreBadge;

	}
}
