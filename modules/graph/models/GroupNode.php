<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\groups\models\Groups;
use pozitronik\helpers\ArrayHelper;

/**
 * Реализация нод для групп
 * Class GroupNode
 * @package app\modules\graph\models
 */
class GroupNode extends GraphNode {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(Groups $model, $config = []) {
		parent::__construct($model, $config);
		$this->id = $model->formName().$model->id;
		$this->label = $model->name;
		$this->shape = 'image';
		$this->image = $model->logo;
		$this->color = ArrayHelper::getValue($model->relGroupTypes, 'color', $this->getRandomRGB());
		$this->widthConstraint = true;
	}
}