<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Company extends Model
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * @var string
     */
    protected $table = 'company';

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
