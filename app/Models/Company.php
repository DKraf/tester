<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bin',
        'legal_address',
        'tel_number'
    ];
}
