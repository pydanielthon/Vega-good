<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoursbillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoursbill', function (Blueprint $table) {
            $table->id();
            $table->integer('workers_id')->nullable()->unsigned();
            $table->integer('contrahents_id')->nullable()->unsigned();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('salary')->nullable();
            $table->integer('deposit')->nullable();

            $table->foreign('workers_id')->references('id')->on('workers')->onDelete('cascade');
            $table->foreign('contrahents_id')->references('id')->on('contrahents')->onDelete('cascade');
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
        Schema::dropIfExists('hoursbill');
    }
}