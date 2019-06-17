<?php
declare(strict_types = 1);

namespace app\modules\graph\models;


/**
 * Interface GraphInterface
 * @package app\models\prototypes
 */
interface GraphInterface {

	/**
	 * @return GraphNode
	 */
	public function asNode():GraphNode;

}