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
            $table->integer('kilometers');
            $table->foreignId('car_model_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('car_models');
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
        Schema::dropIfExists('cars');
    }
}
