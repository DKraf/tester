<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HomePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page', function (Blueprint $table) {
            $table->id();
            $table->string('h1');
            $table->string('t1');
            $table->string('t2');
            $table->string('h3');
            $table->string('t3');
            $table->string('h4');
            $table->string('t4');
            $table->string('h5');
            $table->string('t5');
            $table->string('image');
            $table->string('f1');
            $table->string('f2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
