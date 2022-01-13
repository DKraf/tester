<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'company_id',
        'position_id',
        'password',
        'email',
        'login'
    ];

    /**
     * Атрибуты, которые должны быть скрыты для массивов.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Атрибуты, которые нужно приводить к собственным
     * типам.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return User::orderBy('users.id','DESC')
            ->where('users.first_name', 'LIKE', "%$search%")
            ->orWhere('users.last_name', 'LIKE', "%$search%" )
            ->leftJoin('position', 'users.position_id', '=', 'position.id')
            ->leftJoin('company', 'users.company_id', '=', 'company.id')
            ->select('users.*', 'position.name as position_name', 'company.name as company_name')
            ->paginate(30);
    }
}
