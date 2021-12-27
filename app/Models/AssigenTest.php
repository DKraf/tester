<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssigenTest extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "assigen_test";

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'test_theme_id',
        'test_type_id',
        'user_id',
        'date_start',
        'date_end'
    ];
}
