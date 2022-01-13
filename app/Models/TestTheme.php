<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTheme extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "test_theme";

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
        return TestTheme::orderBy('id','DESC')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(30);
    }
}
