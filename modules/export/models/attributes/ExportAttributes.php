<?php
declare(strict_types = 1);

namespace app\modules\export\models\attributes;

use app\models\relations\RelUsersAttributes;
use app\modules\groups\models\Groups;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\users\models\Users;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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
	 * @param int $colOffset - смещение в таблице от начала (по колонкам), null - игнорировать смещение
	 * @param int $rowOffset - смещение в таблице от начала (по строкам), null - игнорировать смещение
	 * @param bool $formatting - форматировать таблицу стилями
	 * @return array<int, int> - итоговое смещение в таблице по колонке и строке
	 * @throws SpreadsheetException
	 * @throws Throwable
	 */
	private static function GetUserAttributes(Worksheet $worksheet, Users $user, array $relAttributes, int $colOffset = 0, int $rowOffset = 0, bool $formatting = true):array {
		$AttributeNameStyleArray = [
			'font' => [
				'bold' => true,
				'size' => 16
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
		$UsernameStyleArray = [
			'font' => [
				'bold' => true,
				'size' => 18
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFA0A0A0'
				],
				'endColor' => [
					'argb' => 'FFA0A0A0'
				]
			]

		];
		$UserAttributesColumnStyleArray = [
			'borders' => [
				'left' => [
					'borderStyle' => Border::BORDER_THIN
				],
				'right' => [
					'borderStyle' => Border::BORDER_THIN
				],
				'top' => [
					'borderStyle' => Border::BORDER_THIN
				],
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN
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

		$startColIndex = $colOffset + 1;
		$startRowIndex = $rowOffset + 1;

		$col = $startColIndex;
		$row = $startRowIndex;
		$maxCol = $col;//высчитываем максимальное смещение по колонке, оно нужно для форматирования первой строки с именем пользователя и возврата для расчёта корректного смещения при заполнении таблицы данными разных пользователей
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
			$col = $startColIndex;
			$worksheet->setCellValueByColumnAndRow($col, $row, $attribute->name.(empty($attributeTypeNames)?'':" ($attributeTypeNames)"));
			$properties = $attribute->properties;

			$row++;
			$col = $colOffset;
			foreach ($properties as $property) {
				$col++;
				$property->userId = $user->id;
				$value = $property->getValue();
				$worksheet->setCellValueByColumnAndRow($col, $row, $property->name);
				if ($formatting) $worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($AttributeFieldStyleArray);
				$worksheet->setCellValueByColumnAndRow($col, $row + $startRowIndex, $value);
				/*$cell никогда не будет null, дополнительная проверка просто чтобы не ругалась инспекция*/
				if (null !== $cell = $worksheet->getCellByColumnAndRow($col, $row + $startRowIndex)) $cell->getStyle()->getAlignment()->setWrapText(true);
			}
			$worksheet->mergeCellsByColumnAndRow($startColIndex, $row - $startRowIndex, $col, $row - $startRowIndex);
			if ($formatting) $worksheet->getStyleByColumnAndRow($startColIndex, $row - $startRowIndex)->applyFromArray($AttributeNameStyleArray);
			$row++;
			$worksheet->getColumnDimensionByColumn($col)->setAutoSize(true);
			if ($col > $maxCol) $maxCol = $col;
		}
		/*Объединяем и форматируем ячейки заголовка пользователя*/

		$worksheet->mergeCellsByColumnAndRow($startColIndex, $startRowIndex, $maxCol, $startRowIndex);
		if ($formatting) $worksheet->getStyleByColumnAndRow($startColIndex, $startRowIndex, $maxCol, $startRowIndex)->applyFromArray($UsernameStyleArray);
		/*Выделяем весь участок таблицы с атрибутами пользователя*/
		if ($formatting) $worksheet->getStyleByColumnAndRow($startColIndex, $startRowIndex, $maxCol, $row)->applyFromArray($UserAttributesColumnStyleArray);

		return [
			'col' => $maxCol,
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
			'col' => 0,
			'row' => 0
		];
		/** @var Users $user */
		foreach ($group->relUsers as $user) {
			$offset = self::GetUserAttributes($spreadsheet->getActiveSheet(), $user, $user->relUsersAttributes, $offset['col']);
		}

		$writer->save('php://output');
	}

	/**
	 * @param array<int> $ids
	 * @throws Exception
	 * @throws SpreadsheetException
	 * @throws Throwable
	 */
	public static function GroupsExport(array $ids):void {
		$spreadsheet = new Spreadsheet();
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->setActiveSheetIndex(0);
		$offset = [
			'col' => 0,
			'row' => 0
		];
		$users = Users::find()->distinct()->joinWith('relGroups')->where(['sys_groups.id' => $ids])->all();
		/** @var Users $user */
		foreach ($users as $user) {
			$offset = self::GetUserAttributes($spreadsheet->getActiveSheet(), $user, $user->relUsersAttributes, $offset['col']);
		}

		$writer->save('php://output');
	}

	/**
	 * @param array<int> $ids
	 * @throws Exception
	 * @throws SpreadsheetException
	 * @throws Throwable
	 */
	public static function UsersExport(array $ids):void {
		$spreadsheet = new Spreadsheet();
		$writer = new Xlsx($spreadsheet);
		$spreadsheet->setActiveSheetIndex(0);
		$offset = [
			'col' => 0,
			'row' => 0
		];
		foreach ($ids as $id) {
			if (null !== $user = Users::findModel($id)) {
				$offset = self::GetUserAttributes($spreadsheet->getActiveSheet(), $user, $user->relUsersAttributes, $offset['col']);
			}

		}

		$writer->save('php://output');
	}
}