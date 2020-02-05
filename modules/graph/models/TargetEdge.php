<?php
declare(strict_types = 1);

namespace app\modules\graph\models;


use app\modules\targets\models\Targets;

/**
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $label
 * @property string $color
 */
class TargetEdge extends GraphEdge {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(Targets $fromTarget, Targets $toTarget, $config = []) {
		parent::__construct($config);
		$this->id = $fromTarget->formName().$fromTarget->id.'x'.$toTarget->formName().$toTarget->id;
		$this->from = $fromTarget->formName().$fromTarget->id;
		$this->to = $toTarget->formName().$toTarget->id;
//		$this->label = $toTarget->leader->username;
//		$this->color = RelGroupsGroups::getRelationColor($fromTarget->id, $toTarget->id);//todo
	}
}