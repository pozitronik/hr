<?php
declare(strict_types = 1);

namespace app\modules\export\models\attributes;

use app\models\relations\RelUsersAttributes;
use app\modules\groups\models\Groups;
use app\modules\references\models\refs\RefAttributesTypes;
use app\modules\users\models\Users;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;
use yii\base\Model;

/**
 * Class ExportCompetency
 * @package app\modules\export\models\competency
 */
class ExportAttributes extends Model {

	/**
	 * Заполняет $worksheet данными о атрибутах пользователя $user
	 * @param Worksheet $worksheet
	 * @param Users $user
	 * @param RelUsersAttributes[] $relAttributes - массив релейшенов атрибутов
	 * @param int|null $colOffset - смещение в таблице от начала (по колонкам), null - игнорировать смещение
	 * @param int|null $rowOffset - смещение в таблице от начала (по строкам), null - игнорировать смещение
	 * @return array<int, int> - итоговое смещение в таблице по колонке и строке
	 */
	private static function GetUserAttributes(Worksheet $worksheet, Users $user, array $relAttributes, ?int $colOffset = null, ?int $rowOffset = null):array {
		$AttributeNameStyleArray = [
			'font' => [
				'bold' => true,
				'size' => 18
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => Fill::FILL_GRADIENT_LINEAR,
				'rotation' => 90,
				'startColor' => [
					'argb' => 'FFA0A0A0'
				],
				'endColor' => [
					'argb' => 'FFFFFFFF'
				]
			]
		];
		$AttributeFieldStyleArray = [
			'font' => [
				'bold' => true
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			]
		];
		$row = (null === $rowOffset)?1:$rowOffset + 1;
		$col = (null === $colOffset)?1:$colOffset + 1;;
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
				$property->userId = $user->id;
				$value = $property->getValue();
				$worksheet->setCellValueByColumnAndRow($col, $row, $property->name)->getStyleByColumnAndRow($col, $row)->applyFromArray($AttributeFieldStyleArray);
				$worksheet->setCellValueByColumnAndRow($col, $row + 1, $value);
				/*$cell никогда не будет null, дополнительная проверка просто чтобы не ругалась инспекция*/
				if (null !== $cell = $worksheet->getCellByColumnAndRow($col, $row + 1)) $cell->getStyle()->getAlignment()->setWrapText(true);
			}
			$worksheet->mergeCellsByColumnAndRow(1, $row - 1, $col, $row - 1);
			$worksheet->getStyleByColumnAndRow(1, $row - 1)->applyFromArray($AttributeNameStyleArray);
			$row++;
			$worksheet->getColumnDimensionByColumn($col)->setAutoSize(true);
		}
		return [
			'col' => $col,
			'row' => $row
		];
	}

	/**
	 * @param $id
	 * @throws SpreadsheetException
	 * @throws Exception
	 * @throws Throwable
	 */
	public static function UserExport($id):void {
		if (null === $user = Users::findModel($id)) return;
		$relAttributes = RelUsersAttributes::getUserAttributes($id);
		$spreadsheet = new Spreadsheet();
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->setActiveSheetIndex(0);

		self::GetUserAttributes($spreadsheet->getActiveSheet(), $user, $relAttributes);

		$writer->save('php://output');
	}

	/**
	 * @param $id
	 * @throws SpreadsheetException
	 * @throws Throwable
	 */
	public static function GroupExport($id):void {
		if (null === $group = Groups::findModel($id)) return;
		$spreadsheet = new Spreadsheet();
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->setActiveSheetIndex(0);
		$offset = [
			'col' => 1,
			'row' => 1
		];
		/** @var Users $user */
		foreach ($group->relUsers as $user) {
			$offset = self::GetUserAttributes($spreadsheet->getActiveSheet(), $user, $user->relUsersAttributes, $offset['col'], $offset['row']);
		}

		$writer->save('php://output');
	}
}