<?php
declare(strict_types = 1);

namespace app\modules\salary\controllers;

use pozitronik\helpers\ArrayHelper;
use app\models\core\ajax\BaseAjaxController;
use app\modules\salary\models\references\RefUserPositions;
use Throwable;
use Yii;

/**
 * Class AjaxController
 */
class AjaxController extends BaseAjaxController {

	/**
	 * Функция для DepDrop-виджета, отдающая список грейдов, привязанных к должности
	 * @return array
	 * @throws Throwable
	 */
	public function actionGetPositionGrades():array {

		$position_id = ArrayHelper::getValue(Yii::$app->request->post('depdrop_parents'), 0);
		if ((null === $position = RefUserPositions::findModel($position_id)) || empty($grades = $position->relRefGrades)) {//Неправильной должности тут быть не может, значит не заданы грейды
			return [
				'output' => [/*['id' => -1, 'name' => 'Не заполнен список грейдов']*/]
			];
		}
		$out = ArrayHelper::mapEx($grades, ['id' => 'id', 'name' => 'name']);
		return ['output' => $out, 'selected' => ArrayHelper::getValue($out, '0.id')];

	}
}