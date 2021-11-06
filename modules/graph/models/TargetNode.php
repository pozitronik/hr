<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\components\pozitronik\helpers\Utils;
use app\modules\targets\models\Targets;
use app\components\pozitronik\helpers\ArrayHelper;

/**
 * Реализация нод для цели
 */
class TargetNode extends GraphNode {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(Targets $model, $config = []) {
		parent::__construct($model, $config);
		$this->id = $model->formName().$model->id;
		$this->label = Utils::SplitString($model->name, 15);
		$this->color = ArrayHelper::getValue($model->relTargetsTypes, 'color', $this->getRandomRGB());
		$this->shape = 'image';
		$this->image = $model->logo;
//		$this->image = "data:image/svg+xml;charset=utf-8,".rawurlencode(Yii::$app->view->renderFile('@app/modules/graph/views/groups/info.php', [
//				'userCount' => $model->usersCount,
//				'vacancyCount' => $model->vacancyCount,
//				'outstaffCount' => '?',
//				'id' => $model->name,
//				'color' => $this->color,
//			]));

				$this->widthConstraint = true;
	}
}