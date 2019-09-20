<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $name
 * @var array|string|null $selection
 * @var array $options
 * @var array $items
 * @var bool $inheritStyle
 */

use pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\web\View;

?>

<?= Html::radioList($name, $selection, $items, $options + [
		'item' => static function($index, $label, $name, $checked, $value) use ($inheritStyle) {
			$inputOptions = [];
			if (is_array($label)) {//если будем добавлять другие параметры, они пойдут сюда
				$inputOptions = ArrayHelper::getValue($label, 'options', []);
				$value = ArrayHelper::getValue($label, 'value', sha1(ArrayHelper::getValue($label, 'label')));
				$label = ArrayHelper::getValue($label, 'label');
			}
			return Html::input('radio', $name, $value, ['id' => $value, 'class' => 'hidden'] + $inputOptions).Html::label($label, $value, ['class' => "button $value", 'style' => $inheritStyle?ArrayHelper::getValue($inputOptions, 'style'):null]);
		}
	]) ?>

