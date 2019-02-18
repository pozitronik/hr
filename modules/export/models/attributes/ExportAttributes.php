<?php
declare(strict_types = 1);

namespace app\modules\export\models\attributes;

use app\models\relations\RelUsersAttributes;
use app\modules\users\models\Users;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;

/**
 * Class ExportCompetency
 * @package app\modules\export\models\competency
 */
class ExportAttributes extends Model {

	/**
	 * @param $id
	 */
	public static function UserExport($id) {
		if (null === $user = Users::findModel($id)) return;
		$relAttributes = RelUsersAttributes::getUserAttributes($id);
		$spreadsheet = new Spreadsheet();
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->setActiveSheetIndex(0);
		$worksheet = $spreadsheet->getActiveSheet();
		$AttributeNameStyleArray = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_GRADIENT_LINEAR,
				'rotation' => 90,
				'startColor' => [
					'argb' => 'FFA0A0A0',
				],
				'endColor' => [
					'argb' => 'FFFFFFFF',
				],
			],
		];
		$row = 1;
		$col = 1;
		$worksheet->setCellValueByColumnAndRow($col, $row, $user->username);
		foreach ($relAttributes as $relAttribute) {
			$row++;
			$attribute = $relAttribute->relDynamicAttribute;
			$col = 1;
			$worksheet->setCellValueByColumnAndRow($col, $row, $attribute->name);
			$properties = $attribute->properties;

			$row++;
			$col = 0;
			foreach ($properties as $property) {
				$col++;
				$property->userId = $id;
				$value = $property->getValue();
				$worksheet->setCellValueByColumnAndRow($col, $row, $property->name);
				$col++;
				$worksheet->setCellValueByColumnAndRow($col, $row, $value);
				$worksheet->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
			}
			$spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow(1, $row - 1, $col, $row - 1);
			$spreadsheet->getActiveSheet()->getStyleByColumnAndRow(1, $row - 1)->applyFromArray($AttributeNameStyleArray);
		}
		$spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
		$writer->save('php://output');
	}
}