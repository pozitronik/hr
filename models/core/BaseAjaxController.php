<?php
declare(strict_types = 1);

namespace app\models\core;

use yii\web\Controller;

/**
 * Дефолтные методы аяксовых контроллеров (они у нас могут быть в разных модулях), для унификации API
 * Class AjaxController
 * @package app\models\core
 */
class BaseAjaxController extends Controller implements BaseAjaxInterface {

	/**
	 * {@inheritDoc}
	 */
	public function beforeAction($action):bool {
		$this->enableCsrfValidation = false;//todo
		return parent::beforeAction($action);
	}

}