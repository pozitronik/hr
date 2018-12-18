<?php
declare(strict_types = 1);

namespace app\widgets\user;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\references\refs\RefUserRoles;
use app\models\users\Users;
use yii\base\Widget;

/**
 * Class UserWidget
 * @property Users $user
 * @property Groups $group
 *
 */
class UserWidget extends Widget {
	public $user;
	public $group;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if (null === $this->group) {
			return $this->render('user', [
				'model' => $this->user
			]);
		}

		$options = ArrayHelper::map(RefUserRoles::find()->active()->all(), 'id', 'color');
		array_walk($options, function(&$value, $key) {
			if (!empty($value)) {
				$value = [
					'style' => "background: $value;"
				];
			}
		});
		return $this->render('leader', [//todo: сокращёная форма имени?
			'model' => $this->user,
			'group' => $this->group,
			'options' => $options
		]);

	}
}
