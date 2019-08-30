<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\models\relations\RelUsersGroups;
use app\modules\groups\models\Groups;
use app\modules\references\models\CustomisableReference;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;

/**
 * Справочник типов должностей. Тип должности -  необязательный, не влияющий ни на что атрибут должности.
 *
 * @property string $color
 *
 * @property RelRefUserPositionsTypes|ActiveQuery $relRefUSerPositionsTypes
 * @property RefUserPositions|ActiveQuery $relRefUserPositions
 * @property Users[]|ActiveQuery $relUsers
 * @property RelUsersGroups[]|ActiveQuery $relUserGroups
 * @property Groups[]|ActiveQuery $relGroups
 * @property int $count
 */
class RefUserPositionTypes extends CustomisableReference {
	public $menuCaption = 'Типы должностей';
	public $menuIcon = false;

	public $count = 0;//Псевдоаттрибут, заполняется при подсчёте среза по типам пользователей

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_types';
	}

	/**
	 * @return RelRefUserPositionsTypes|ActiveQuery
	 */
	public function getRelRefUSerPositionsTypes() {
		return $this->hasOne(RelRefUserPositionsTypes::class, ['position_type_id' => 'id']);
	}

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRelRefUserPositions() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position_id'])->via('relRefUSerPositionsTypes');
	}

	/**
	 * @return Users[]|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['position' => 'id'])->via('relRefUserPositions');
	}

	/**
	 * @return RelUsersGroups[]|ActiveQuery
	 */
	public function getRelUserGroups() {
		return $this->hasMany(RelUsersGroups::class, ['user_id' => 'id'])->via('relUsers');
	}

	/**
	 * @return Groups[]|ActiveQuery
	 */
	public function getRelGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUserGroups');
	}

}