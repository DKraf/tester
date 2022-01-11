<?php


namespace App\Http\Repository;

use App\Http\Service\TestService;
use App\Models\AssigenTest;
use App\Models\AssigenTestQuestion;

/**
 * Class AssignTest
 * @package App\Http\Repository
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class AssignTest
{
    /**
     * @param $question
     * @return array
     */
    public static function getTestHistory($id)
    {
        $status_service = new TestService();

        $data = AssigenTest::orderBy('assigen_test.id','DESC')->where('assigen_test.id', '=', $id)
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->leftJoin('company', 'company.id', '=', 'users.company_id')
            ->leftJoin('position', 'position.id', '=', 'users.position_id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type_name',
                'test_type.questions_count as questions_count',
                'test_type.min_question_count as min_question_count',
                'test_type.time_for_testing as time_for_testing',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic',
                'position.name as position_name',
                'company.name as company_name'

            )
            ->get();

        $tests = AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
            ->leftJoin('test', 'test.id', '=', 'assigen_test_question.question_id')
            ->select
            (
                'test.question as question_name',
                'test.id as question_id',
                'test.A as A',
                'test.B as B',
                'test.C as C',
                'test.answer_true as answer_true',
                'assigen_test_question.selected_answer as selected_answer'
            )
            ->get();

        $data = $status_service->checkDateForStatus($data);

        return ['data'=>$data, 'tests' => $tests];
    }
}

