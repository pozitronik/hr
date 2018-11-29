<?php
declare(strict_types = 1);

namespace app\models\imports;

use yii\base\Model;

/**
 * Class
 * Импорт из ФОС (хз, wtf)
 * @package app\models\imports
 */
class FosRecord extends Model {
	public $id;
	public $shd_id;//?
	public $position;
	public $username;
	public $block;
	public $division_level1;
	public $division_level2;
	public $division_level3;
	public $division_level4;
	public $division_level5;
	public $urm;
	public $city;
	public $tribe_block;
	public $tribe_id;
	public $tribe_code;
	public $tribe;
	public $tribe_leader_th;
	public $tribe_leader_username;
	public $tribe_leader_it_th;
	public $tribe_leader_it_username;
	public $cluster_id;
	public $cluster_code;
	public $cluster;
	public $cluster_leader_th;
	public $cluster_leader;
	public $command_id;
	public $command_code;
	public $command;
	public $command_type;
	public $product_owner;
	public $position_id;
	public $position_code;
	public $position_in_command;
	public $chapter_id;
	public $chapter_code;
	public $chapter;
	public $chapter_leader_th;
	public $chapter_leader;
	public $email_sigma;
	public $email;


}