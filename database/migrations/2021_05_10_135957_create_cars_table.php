<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('stammnummer', 11)->unique();
            $table->string('vin', 17);
            $table->string('colour')->nullable();
            $table->text('notes')->nullable();
            $table->text('known_damage')->nullable();
            $table->date('initial_date');
            $table->date('last_check_date');
            $table->date('bought_at');
            $table->integer('buy_price');
            $table->integer('kilometers');
            $table->unsignedBigInteger('seller_contact_id');
            $table->foreignId('car_model_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('car_models');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('seller_contact_id')->references('id')->on('contacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
