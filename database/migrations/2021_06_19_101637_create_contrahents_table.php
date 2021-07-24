<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrahentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrahents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 100);
            $table->decimal('salary_cash', 10, 2)->nullable();
            $table->decimal('salary_invoice', 10, 2)->nullable();
            $table->boolean('status')->default(false);
            $table->string('notes', 2000)->nullable();
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
        Schema::dropIfExists('contrahents');
    }
}
