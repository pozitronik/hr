<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\user_right_select;

use pozitronik\widgets\CachedWidget;
use pozitronik\helpers\ArrayHelper;
use pozitronik\core\models\core_module\PluginsSupport;
use app\modules\privileges\models\DynamicUserRights;
use app\modules\privileges\models\Privileges;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class UserRightSelectWidget
 * Виджет списка групп (для добавления пользователя)
 * @package app\components\user_right_select
 *
 * @property ActiveRecord|null $model При использовании виджета в ActiveForm ассоциируем с моделью...
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Права, исключённые из списка (например те, в которых пользователь уже есть)
 * @property bool $multiple
 * @property bool $orderByModule Группировать по модулю
 * @property int $mode Режим выбора прав
 */
class UserRightSelectWidget extends CachedWidget {
	public const MODE_MODELS = 1;//только заданные кодом (в моделях)
	public const MODE_DYNAMIC = 2;//только динамические
	public const MODE_BOTH = 3;//объединять

	public $model;
	public $attribute;
	public $notData;
	public $multiple = false;
	public $orderByModule = true;
	public $mode = self::MODE_BOTH;//можно сделать флагом, но для двух вариантов не буду заморачиваться

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserRightSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function run():string {

		switch ($this->mode) {
			case self::MODE_MODELS:
				$data = PluginsSupport::GetAllRights($this->notData);
			break;
			case self::MODE_DYNAMIC:
				$data = DynamicUserRights::find()->select(['id', 'name'])->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->active()->all();
			break;
			default:
			case self::MODE_BOTH:
				$data = array_merge(PluginsSupport::GetAllRights($this->notData), DynamicUserRights::find()->select(['id', 'name'])->where(['not in', 'id', ArrayHelper::getColumn($this->notData, 'id')])->active()->all());
			break;

		}

		return $this->render('user_right_select', [
			'model' => $this->model,
			'attribute' => $this->attribute,
			'data' => ArrayHelper::map($data, 'id', 'name', $this->orderByModule?'module':null),
			'multiple' => $this->multiple,
			'options' => Privileges::dataOptions()
		]);
	}
}
