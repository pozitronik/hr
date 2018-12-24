<?php
declare(strict_types = 1);

namespace app\widgets\group_type_select;

use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use yii\base\Widget;
use yii\web\NotFoundHttpException;

/**
 * @package app\components\group_type_select
 *
 * @property array $data
 * @property array $value
 * @property int $groupId
 * @property bool $showStatus
 */
class GroupTypeSelectWidget extends Widget {
	public $data;
	public $value;
	public $groupId;
	public $showStatus = true;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupTypeSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$group = Groups::findModel($this->groupId, new NotFoundHttpException("Group {$this->groupId} not found!"));

		return $this->render('group_type_select', [
			'data' => $this->data??RefGroupTypes::mapData(),
			'value' => $this->value??$group->type,
			'groupId' => $this->groupId,
			'options' => RefGroupTypes::dataOptions(),
			'showStatus' => $this->showStatus
		]);
	}
}
