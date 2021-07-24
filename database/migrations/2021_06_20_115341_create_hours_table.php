<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hours', function (Blueprint $table) {
            $table->id();
            $table->integer('workers_id')->unsigned();
            $table->decimal('workers_price_hour');

            $table->integer('contrahents_id')->unsigned();
            $table->decimal('contrahents_salary_cash');
            $table->decimal('contrahents_salary_invoice');

            $table->json('worker_billed_json')->nullable();
            $table->json('contrahent_billed_json')->nullable();
            $table->date('work_day');

            $table->decimal('hours', 10, 2 );
            $table->boolean('status_of_billings_contrahent')->default(false);
            $table->boolean('status_of_billings_worker')->default(false);

            $table->foreign('contrahents_id')->references('id')->on('contrahents')->onDelete('cascade');
            $table->foreign('workers_id')->references('id')->on('workers')->onDelete('cascade');
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
        Schema::dropIfExists('hours');
    }
}
