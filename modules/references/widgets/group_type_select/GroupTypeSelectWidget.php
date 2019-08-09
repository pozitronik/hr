<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\group_type_select;

use app\models\core\CachedWidget;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use Throwable;
use yii\web\NotFoundHttpException;

/**
 * @package app\components\group_type_select
 *
 * @property array $data
 * @property array|null $value
 * @property int $groupId
 * @property bool $showStatus
 */
class GroupTypeSelectWidget extends CachedWidget {
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
	 * @return null|string
	 * @throws Throwable
	 */
	public function run():?string {
		if (null === $group = Groups::findModel($this->groupId, new NotFoundHttpException("Group {$this->groupId} not found!"))) return null;

		return $this->render('group_type_select', [
			'data' => $this->data??RefGroupTypes::mapData(),
			'value' => $this->value??$group->type,
			'groupId' => $this->groupId,
			'options' => RefGroupTypes::dataOptions(),
			'showStatus' => $this->showStatus
		]);
	}
}
