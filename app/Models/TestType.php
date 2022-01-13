<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'test_type';

    /**
     * Атрибуты, которые можно назначать массово.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'questions_count',
        'min_question_count',
        'time_for_testing'
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
