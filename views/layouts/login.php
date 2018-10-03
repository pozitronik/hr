<?php
declare(strict_types = 1);

/**
 * Основной шаблон страницы авторизации
 * @author Moiseenko-EA
 * @date 10.08.2017
 * @time 16:46
 *
 * @var \yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;
use app\helpers\ArrayHelper;

?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language; ?>">
<head>
	<meta charset="<?= Yii::$app->charset; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?= Html::csrfMetaTags(); ?>
	<title><?= ArrayHelper::getValue(Yii::$app->params, 'ServerNode') ?>&#160;LightCab &mdash; <?= Html::encode($this->title); ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="cls-container">
	<div id="bg-overlay" class="bg-img"></div>
	<div class="cls-content">
		<?= $content; ?>
	</div>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>