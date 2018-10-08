<?php
declare(strict_types = 1);

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use app\assets\AppAsset;
use yii\helpers\Html;
use app\widgets\navbar\NavbarWidget;

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
	<title><?= $this->title; ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="mainnav-fixed print-content">
	<?= NavbarWidget::widget([]); ?>


	<div class="boxed">
		<div id="content-container">
			<div id="page-content">
				<?= $content; ?>
			</div>
		</div>
	</div>
	<button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>