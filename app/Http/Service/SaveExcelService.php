<?php


namespace App\Http\Service;


use App\Http\Repository\AdminAssignTestQuestion;
use App\Http\Repository\AssignTest;
use App\Models\AssigenTest;
use App\Models\AssigenTestQuestion;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class SaveExceleService
 * @package App\Http\Service
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class SaveExcelService
{
    public function download($id)
    {
       $in_data = AssignTest::getTestHistory($id);

       return $this->save($in_data, $id);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function save($data, $id)
    {
        $i = 2;
        $host =$_SERVER['DOCUMENT_ROOT'];
        $directory = '/excelsaves/';
        $filename= $host.$directory.$id.'.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'Наименование');
        $sheet->setCellValue('C1', 'Склад');
        $sheet->setCellValue('D1', 'Адрес');
        $sheet->setCellValue('E1', 'Серийный номер	');
        $sheet->setCellValue('F1', 'Инвентарынй номер');
        $mydata = $data['data'];
        foreach ($mydata as $item){
            $sheet->setCellValue('A'. $i, $item->id);
//            $sheet->setCellValue('B'. $i, $item->name);
//            $sheet->setCellValue('C'. $i, $item->store_name);
//            $sheet->setCellValue('D'. $i, $item->store_address);
//            $sheet->setCellValue('E'. $i, $item->serial_number);
//            $sheet->setCellValue('F'. $i, $item->inventory_number);
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        return $filename;
    }
}
