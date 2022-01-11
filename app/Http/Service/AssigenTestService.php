<?php


namespace App\Http\Service;


use App\Http\Repository\AssignTest;


/**
 * Class AssigenTestService
 * @package App\Http\Service
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class AssigenTestService
{
    public function getTestHistory($id)
    {
      return AssignTest::getTestHistory($id);
    }
}
