<?php
declare(strict_types = 1);

/**
 * @var int $vacancyCount
 * @var int $userCount
 * @var View $this
 * @var int $outstaffCount
 * @var int $id
 * @var string $color
 */

use app\helpers\Utils;
use yii\web\View;

$fontColor = Utils::RGBColorContrast($color);

?>
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="140">
	<rect x="0" y="0" width="100%" height="100%" fill="white" stroke-width="20" stroke="<?= $fontColor ?>"></rect>
	<foreignObject x="15" y="10" width="100%" height="100%">
		<div xmlns="http://www.w3.org/1999/xhtml"  style="font-size:60px; font-color:<?= $fontColor ?>')">
			<center><?= $userCount ?>/<?= $vacancyCount ?>/<?= $outstaffCount ?></center>

		</div>
	</foreignObject>
</svg>