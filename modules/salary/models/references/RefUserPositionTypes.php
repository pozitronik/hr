<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\models\relations\RelUsersGroups;
use app\modules\groups\models\Groups;
use app\components\pozitronik\references\models\CustomisableReference;
use app\components\pozitronik\references\ReferencesModule;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\relations\RelUserPositionsTypes;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\components\pozitronik\badgewidget\BadgeWidget;
use yii\db\ActiveQuery;
use yii\helpers\Html;

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
	 * @return ActiveQuery
	 */
	public function getRelRefUSerPositionsTypes():ActiveQuery {
		return $this->hasOne(RelRefUserPositionsTypes::class, ['position_type_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelRefUserPositions():ActiveQuery {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position_id'])->via('relRefUSerPositionsTypes');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsers():ActiveQuery {
		return $this->hasMany(Users::class, ['position' => 'id'])->via('relRefUserPositions');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUserGroups():ActiveQuery {
		return $this->hasMany(RelUsersGroups::class, ['user_id' => 'id'])->via('relUsers');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroups():ActiveQuery {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUserGroups');
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		/* Посчитает только отношения к должностям, привязка типов должностей непосредственно к пользователям учитываться не будет. Это корректно в данном разрезе.
		 * Это может привести к разночтениям после фильтрации на странице поиска (там условие учитывает все варианты).
		 */
		return (int)RelUserPositionsTypes::find()->where(['position_type_id' => $this->id])->count();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getColumns():array {
		return [
			[
				'attribute' => 'id',
				'options' => [
					'style' => 'width:36px;'
				]
			],
			[
				'attribute' => 'name',
				'value' => static function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => self::colorStyleOptions()
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount',
				'filter' => false,
				'value' => static function($model) {
					/** @var self $model */
					return BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'usedCount',
						'linkScheme' => [UsersModule::to(['users/index']), 'UsersSearch[positionType]' => 'id'],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw'
			]
		];
	}

}