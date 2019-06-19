<?php
declare(strict_types = 1);

/**
 * @var int $vacancyCount
 * @var int $userCount
 * @var View $this
 * @var int $outstaffCount
 * @var int $id
 */

use yii\web\View;

?>
<svg xmlns="http://www.w3.org/2000/svg" width="140" height="140">
	<rect x="0" y="0" width="100%" height="100%" fill="#f9b300" stroke-width="20" stroke="#0077ff"></rect>
	<foreignObject x="15" y="10" width="100%" height="100%">
		<div xmlns="http://www.w3.org/1999/xhtml" style="font-size:40px; margin-left: auto%; margin-right: auto')">
			<?= $userCount ?>/<?= $vacancyCount ?>/<?= $outstaffCount ?><br/>
		</div>
	</foreignObject>
</svg>