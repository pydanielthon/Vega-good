<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->integer('workers_id')->nullable()->unsigned();
            $table->integer('contrahents_id')->nullable()->unsigned();
            $table->integer('category_id')->unsigned();

            $table->timestamp('date');
            $table->decimal('price', 12, 2);
            $table->string('notes')->nullable();
            $table->boolean('status_of_billings')->default(false);

            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
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
        Schema::dropIfExists('billings');
    }
}