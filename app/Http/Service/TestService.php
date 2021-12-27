<?php


namespace App\Http\Service;


use App\Http\Repository\AdminAssignTestQuestion;
use App\Models\AssigenTestQuestion;

/**
 * Class TestService
 * @package App\Http\Service
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class TestService
{
    public function checkDateForStatus($data)
    {
        $today = strtotime(date("ymd"));
        $status = '';
        foreach ($data as $item) {
            if (strtotime($item->date_start) >= $today) {
                $status = 'Не доступен';
            } elseif (strtotime($item->date_end) <= $today) {
                $status = 'Просрочен';
            } elseif (strtotime($item->date_end) >= $today && strtotime($item->date_start) <= $today) {
                $status = 'Доступен';
            }
            $item->date_start = date('d.m.Y', strtotime($item->date_start));
            $item->date_end = date('d.m.Y', strtotime($item->date_end));
            $item->status = $status;
        }
        return $data;
    }

    /**
     * Метод добавления вопросов к назначенному тесту
     * @param $id
     * @param $questions_count
     * @param $tests
     */
    public function AssigenTestQuestion($id, $questions_count, $tests )
    {
      $assign_test = array_rand($tests, $questions_count);
        $out_data = [];
        foreach ($assign_test as $key => $value){
            $out_data[$key]= [
                'assigen_test_id' => $id,
                'question_id'     => $value

            ];
        }

        AdminAssignTestQuestion::saveAssignQuestion($out_data);

        return true;
    }
}
