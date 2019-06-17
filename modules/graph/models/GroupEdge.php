<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\models\relations\RelGroupsGroups;
use app\modules\groups\models\Groups;

/**
 * Class GroupEdge
 * @package app\modules\graph\models
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $label
 * @property string $color
 */
class GroupEdge extends GraphEdge {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(Groups $fromGroup, Groups $toGroup, $config = []) {
		parent::__construct($config);
		$this->id = $fromGroup->formName().$fromGroup->id.'x'.$toGroup->formName().$toGroup->id;
		$this->from = $fromGroup->formName().$fromGroup->id;
		$this->to = $toGroup->formName().$toGroup->id;
		$this->label = $toGroup->leader->username;
		$this->color = RelGroupsGroups::getRelationColor($fromGroup->id, $toGroup->id);
	}
}