<?php


namespace App\Http\Repository;

use App\Models\AssigenTestQuestion;

/**
 * Class AdminAssignTestQuestion
 * @package App\Http\Repository
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class AdminAssignTestQuestion
{
    /**
     * @param $question
     * @return bool
     */
    public static function saveAssignQuestion($question)
    {
        foreach ($question as $item){
            AssigenTestQuestion::create($item);
        }
        return true;
    }
}

