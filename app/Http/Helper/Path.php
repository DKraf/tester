<?php


namespace App\Http\Helper;

/**
 * Class Path
 * @package App\Http\Helper
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class Path
{
    /**
     * Генерим путь и имя выгружаемого файла Excel
     * @param $name
     * @param $id
     * @return string[]
     */
    public static function getExcelPath($name, $id)
    {
        $host =$_SERVER['DOCUMENT_ROOT'];
        $directory = '/excelsaves/';
        return ['path'=>$host.$directory.$name.'-test-'.$id.'.xlsx' , 'filename' => $name.'-test-'.$id];
    }

}

