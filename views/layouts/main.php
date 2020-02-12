<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\AppAsset;
use pozitronik\helpers\Utils;
use app\widgets\alert\Alert;
use yii\helpers\Html;
use app\widgets\navbar\NavbarWidget;
use app\models\user\CurrentUser;
use yii\web\View;

AppAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="commit" content="<?= Utils::LastCommit() ?>">
	<?= Html::csrfMetaTags() ?>
	<title><?= $this->title ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div id="container" class="mainnav-fixed print-content navbar-fixed">
	<?= NavbarWidget::widget([
		'user' => CurrentUser::User()
	]) ?>
	<div class="boxed">
		<div id="content-container">
			<div id="page-content">
				<?= Alert::widget() ?>
				<?= $content ?>
			</div>
		</div>
	</div>
	<button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
</div>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>