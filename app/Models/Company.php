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


    /**
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return Company::orderBy('id','DESC')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(30);
    }
}
