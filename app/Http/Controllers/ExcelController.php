<?php

namespace App\Http\Controllers;

use App\Http\Service\SaveExcelService;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    Public function downloadResult($id)
    {
        $excel_service = new SaveExcelService();
        $file = $excel_service->download($id);
        return response()->download($file, $id.'.xlsx');


    }
}
