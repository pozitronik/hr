<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\users\models\Users;
use app\components\pozitronik\helpers\ArrayHelper;

/**
 * Реализация нод для пользователей
 * Class UsersNode
 * @package app\modules\graph\models
 */
class UserNode extends GraphNode {
	/**
	 * {@inheritDoc}
	 */
	public function __construct(Users $model, $config = []) {
		parent::__construct($model, $config);
		$this->id = $model->formName().$model->id;
		$this->label = $model->username;
		$this->color = ArrayHelper::getValue($model->relRefUserPositions, 'color', $this->getRandomRGB());
		$this->shape = 'image';
		$this->image = $model->avatar;
//		$this->image = "data:image/svg+xml;charset=utf-8,".rawurlencode(Yii::$app->view->renderFile('@app/modules/graph/views/groups/info.php', [
//				'userCount' => $model->usersCount,
//				'vacancyCount' => $model->vacancyCount,
//				'outstaffCount' => '?',
//				'id' => $model->name,
//				'color' => $this->color,
//			]));

		$this->widthConstraint = true;
	}
}