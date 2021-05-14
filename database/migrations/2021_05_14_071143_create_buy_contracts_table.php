<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_contracts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('price');
            $table->foreignId('contact_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('contacts');
            $table->foreignId('car_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('cars');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_contracts');
    }
}
