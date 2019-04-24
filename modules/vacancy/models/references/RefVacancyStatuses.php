<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;


use app\modules\references\models\Reference;

/**
 * Class RefVacancyStatuses
 * @package app\modules\salary\models\vacancy
 */
class RefVacancyStatuses extends Reference {
	public $menuCaption = 'Статусы вакансий';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_vacancy_statuses';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['color'], 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'usedCount' => 'Использований',
			'color' => 'Цвет'
		];
	}
}
