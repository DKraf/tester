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
        'date_end',
        'is_started',
        'true_answer_count',
        'time_spent',
        'date_done',
    ];


    /**
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return AssigenTest::orderBy('assigen_test.id','DESC')
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '=', null)
            ->Where('users.last_name', 'LIKE', "%$search%" )
            ->paginate(30);

    }


    /**
     * @param $search
     * @return mixed
     */
    public function searchHistory($search)
    {
        return AssigenTest::orderBy('assigen_test.id','DESC')
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '!=', null)
            ->Where('users.last_name', 'LIKE', "%$search%" )
            ->paginate(30);

    }
}
