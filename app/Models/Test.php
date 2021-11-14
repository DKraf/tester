<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'test_theme_id',
        'question',
        'answer_true',
        'answer_1',
        'answer_2',
        'answer_3'
    ];
}
