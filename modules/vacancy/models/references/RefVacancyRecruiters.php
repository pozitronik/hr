<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\references;

use app\modules\references\models\Reference;

/**
 * Class RefVacancyRecruiters
 * @package app\modules\vacancy\models\references
 */
class RefVacancyRecruiters extends Reference {
	public $menuCaption = 'Рекрутеры';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_vacancy_recruiters';
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
