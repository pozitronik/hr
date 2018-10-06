<?php
declare(strict_types = 1);

/**
 *
 * @var View $this
 * @var string $content
 */

use yii\helpers\Html;
use app\helpers\ArrayHelper;
use yii\web\View;
use app\assets\LoginAsset;

LoginAsset::register($this);
$this->registerJs("particlesJS.load('bg-overlay', '/js/particles.js/particles.json', function() {
  console.log('callback - particles.js config loaded');
});")
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