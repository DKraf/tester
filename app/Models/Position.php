<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Position extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'position';
    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return Position::orderBy('id','DESC')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(30);
    }
}
