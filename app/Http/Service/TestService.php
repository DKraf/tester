<?php


namespace App\Http\Service;


use App\Http\Repository\AdminAssignTestQuestion;
use App\Models\AssigenTest;
use App\Models\AssigenTestQuestion;
use Carbon\Carbon;

/**
 * Class TestService
 * @package App\Http\Service
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class TestService
{

    /**
     * метод для присвоения статуса тестирования
     * @param $data
     * @return mixed
     */
    public function checkDateForStatus($data)
    {
        $today = strtotime(date("ymd"));
        $status = '';
        foreach ($data as $item) {
            if ($item->is_started) {
                $status = 'Начат ранее';
            } elseif (strtotime($item->date_end) <= $today) {
                $status = 'Просрочен';
            } elseif (strtotime($item->date_end) >= $today && strtotime($item->date_start) <= $today) {
                $status = 'Доступен';
            } elseif (strtotime($item->date_start) >= $today) {
                $status = 'Не доступен';
            }
            $item->date_start = date('d.m.Y', strtotime($item->date_start));
            $item->date_end = date('d.m.Y', strtotime($item->date_end));
            $item->status = $status;
        }
        return $data;
    }


    /**
     * Метод проверки , досутпен ли тест по дате
     * @param $data
     * @return bool
     */
    public function checkTestAccess($data)
    {
        $today = strtotime(date("ymd"));
        foreach ($data as $item) {
            if (strtotime($item->date_start) >= $today) {
                return false;
            } elseif (strtotime($item->date_end) <= $today) {
                return false;
            } elseif (strtotime($item->date_end) >= $today && strtotime($item->date_start) <= $today) {
                return true;
            }
        }
        return true;
    }


    /**
     * Метод проверки был ли тест запущен ранее
     * @param $data
     * @return bool
     */
    public function checkWasStart($data): bool
    {
        foreach ($data as $item) {
            if (($item->is_started)) return true;
        }
        return false;
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

    /**
     * Метод добавления вопросов к назначенному тесту
     * @param $id
     * @param $questions_count
     * @param $tests
     */
    public function deleteAssigenTestQuestion($id)
    {
        AdminAssignTestQuestion::deleteAssignQuestion($id);
        return true;
    }


    /**
     * Метод записи выбранных ответов
     * @param $id
     * @param $data
     * @return bool
     */
    public function saveAnswerToAssignTestQuestions($id , $data): bool
    {
        foreach ($data as $key => $value) {
            AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
                ->where('question_id' , '=' , $key)
                ->update(['selected_answer'=>$value]);
        }
        return true;
    }

    public function calculateTrueAnswers($id, $time): bool
    {

        $true_answer_count = 0;
        $test = AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
            ->leftJoin('test', 'test.id', '=', 'assigen_test_question.question_id')
            ->select
            (
                'test.answer_true',
                'assigen_test_question.selected_answer'
            )
            ->get();

        foreach ($test as $item) {
            if ($item['answer_true'] === $item['selected_answer']) {
                $true_answer_count++;
            }
        }

        $test = AssigenTest::find($id);
        $test->update([
            'true_answer_count' => $true_answer_count,
            'time_spent'        => $time,
            'date_done'         => Carbon::now()
        ]);

        return true;
    }
}
