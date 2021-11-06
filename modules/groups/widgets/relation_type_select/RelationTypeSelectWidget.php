<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\relation_type_select;

use app\components\pozitronik\cachedwidget\CachedWidget;
use app\modules\groups\models\references\RefGroupRelationTypes;
use app\models\relations\RelGroupsGroups;
use Throwable;

/**
 * @package app\components\relation_type_select
 *
 * @property array $data
 * @property array|null $value
 * @property int $parentGroupId
 * @property int $childGroupId
 * @property bool $showStatus
 */
class RelationTypeSelectWidget extends CachedWidget {
	public $data;
	public $value;
	public $parentGroupId;
	public $childGroupId;
	public $showStatus = true;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		RelationTypeSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		return $this->render('relation_type_select', [
			'data' => $this->data??RefGroupRelationTypes::mapData(),
			'value' => $this->value??RelGroupsGroups::getRelationId($this->parentGroupId, $this->childGroupId),
			'parentGroupId' => $this->parentGroupId,
			'childGroupId' => $this->childGroupId,
			'options' => RefGroupRelationTypes::dataOptions(),
			'showStatus' => $this->showStatus
		]);
	}
}
