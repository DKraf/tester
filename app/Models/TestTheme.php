<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTheme extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'test_type_id'
    ];
}
