<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AssigenTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigen_test', function (Blueprint $table) {
            $table->id();
            $table->integer('test_theme_id');
            $table->integer('test_type_id');
            $table->integer('user_id');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->timestamp('date_done')->nullable();
            $table->integer('true_answer_count')->nullable();
            $table->integer('time_spent')->nullable();
            $table->timestamps();
            $table->boolean('is_started')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assigen_test');

    }
}
