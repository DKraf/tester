<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssigenTestQuestion extends Model
{
    use HasFactory;


    /**
     * @var string
     */
    protected $table = "assigen_test_question";

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'assigen_test_id',
        'question_id'
    ];
}
