<?php
declare(strict_types = 1);

namespace app\modules\export\models\attributes;

use app\models\relations\RelUsersAttributes;
use app\modules\references\models\refs\RefAttributesTypes;
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
				'size' => 18
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
		$AttributeFieldStyleArray = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
			],
		];
		$row = 1;
		$col = 1;
		$worksheet->setCellValueByColumnAndRow($col, $row, "Атрибуты пользователя {$user->username}");
		foreach ($relAttributes as $relAttribute) {
			$row++;
			$attribute = $relAttribute->relDynamicAttribute;
			$attributeTypeNames = [];
			/** @var RefAttributesTypes $refAttributeType */
			foreach ($relAttribute->refAttributesTypes as $refAttributeType) {
				$attributeTypeNames[] = $refAttributeType->name;
			}
			$attributeTypeNames = implode('/', $attributeTypeNames);
			$col = 1;
			$worksheet->setCellValueByColumnAndRow($col, $row, $attribute->name.(empty($attributeTypeNames)?'':" ($attributeTypeNames)"));
			$properties = $attribute->properties;

			$row++;
			$col = 0;
			foreach ($properties as $property) {
				$col++;
				$property->userId = $id;
				$value = $property->getValue();
				$worksheet->setCellValueByColumnAndRow($col, $row, $property->name)->getStyleByColumnAndRow($col, $row)->applyFromArray($AttributeFieldStyleArray);
				$worksheet->setCellValueByColumnAndRow($col, $row + 1, $value);
				$worksheet->getCellByColumnAndRow($col, $row + 1)->getStyle()->getAlignment()->setWrapText(true);
			}
			$spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow(1, $row - 1, $col, $row - 1);
			$spreadsheet->getActiveSheet()->getStyleByColumnAndRow(1, $row - 1)->applyFromArray($AttributeNameStyleArray);
			$row++;
			$spreadsheet->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
		}

		$writer->save('php://output');
	}
}