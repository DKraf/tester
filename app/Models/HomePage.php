<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'home_page';

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'h1',
        't1',
        't2',
        'h3',
        't3',
        'h4',
        't4',
        'h5',
        't5',
        'image1',
        'image2',
        'image3',
        'f1',
        'f2',
        'fh1',
        'fh2'
    ];


    /**
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return TestType::orderBy('id','DESC')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(30);
    }
}
