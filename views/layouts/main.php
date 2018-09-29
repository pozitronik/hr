<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use app\assets\AppAsset;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use app\helpers\ArrayHelper;

AppAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language; ?>">
<head>
	<meta charset="<?= Yii::$app->charset; ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?= Html::csrfMetaTags(); ?>
	<title><?= ArrayHelper::getValue(Yii::$app->params, 'ServerNode'); ?>&#160;LightCab &mdash; <?= $this->title; ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="mainnav-fixed print-content">
	<div class="boxed">
		<div id="content-container">
			<div class="mar-top hidden-print">
				<?= Breadcrumbs::widget([
					'links' => ArrayHelper::getValue($this->params, 'breadcrumbs', [])
				]); ?>
			</div>
			<div id="page-content">
				<?= $content; ?>
			</div>
		</div>
	</div>
	<footer id="footer"><p class="pad-lft"><?= '&copy; Card Solutions Development '.date('Y'); ?></p></footer>
	<button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
</div>
<?php if (ArrayHelper::getValue(Yii::$app->params, 'test', false)): ?>
	<div class="test-container">
		<img class="test-img" src="/img/test.png">
	</div>
<?php endif; ?>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>