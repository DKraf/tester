<?php


namespace App\Http\Service;


use App\Http\Helper\Path;
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function save($data, $id)
    {
        $i = 2;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Номер теста');
        $sheet->setCellValue('A2', 'ФИО:');
        $sheet->setCellValue('A3', 'Должность:');
        $sheet->setCellValue('A4', 'Компания:');
        $sheet->setCellValue('A5', 'Тематика:');
        $sheet->setCellValue('A6', 'Тип теста:');
        $sheet->setCellValue('A7', 'Кол-во вопросов:');
        $sheet->setCellValue('A8', 'Мин. кол-во правильных ответов:');
        $sheet->setCellValue('A9', 'Всего отвечено правильно на:');
        $sheet->setCellValue('A10', 'Затраченное время на тестирование:');
        $sheet->setCellValue('A11', 'Результат:');
        $sheet->setCellValue('A12', 'Дата сдачи теста:');


        $mydata = $data['data'];
        $questions = $data['tests'];
        foreach ($mydata as $item){
            $file_data = Path::getExcelPath($item->last_name,$item->id );

            if (!empty($item->true_answer_count) &&  ((int)$item->true_answer_count >= (int)$item->min_question_count)) {
                $status = 'Сдал';
            } else {
                $status = 'Не сдал';
            }
            $sheet->setCellValue('B1', (string)$item->id);
            $sheet->setCellValue('B2', $item->last_name . ' '. $item->first_name . ' ' . $item->patronymic);
            $sheet->setCellValue('B3', $item->position_name);
            $sheet->setCellValue('B4', $item->company_name);
            $sheet->setCellValue('B5', $item->theme);
            $sheet->setCellValue('B6', $item->type_name);
            $sheet->setCellValue('B7', (string)$item->questions_count);
            $sheet->setCellValue('B8', (string)$item->min_question_count);
            $sheet->setCellValue('B9', (string)$item->true_answer_count);
            $sheet->setCellValue('B10', $item->time_spent . ' мин.');
            $sheet->setCellValue('B11', $status);
            $sheet->setCellValue('B12', $item->date_done);
        }
        $spreadsheet->createSheet(1);

        $spreadsheet->setActiveSheetIndex(1);

        $sheet = $spreadsheet->getActiveSheet();
        $number = 1;

        $sheet->setCellValue('A1', 'Номер вопроса:');
        $sheet->setCellValue('B1', 'Содержание вопроса:');
        $sheet->setCellValue('C1', 'Вариант А:');
        $sheet->setCellValue('D1', 'Вариант B:');
        $sheet->setCellValue('E1', 'Вариант C:');
        $sheet->setCellValue('F1', 'Выбранный ответ:');

        foreach ($questions as $item){
            $sheet->setCellValue('A'.$i, $number);
            $sheet->setCellValue('B'.$i, $item->question_name);
            $sheet->setCellValue('C'.$i, $item->A);
            $sheet->setCellValue('D'.$i, $item->B);
            $sheet->setCellValue('E'.$i, $item->C);
            $sheet->setCellValue('F'.$i, $item->selected_answer);
            $i++;
            $number++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($file_data['path']);

        return ['path' => $file_data['path'] , 'filename' => $file_data['filename']];
    }
}
