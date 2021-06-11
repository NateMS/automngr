<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentType;

class CreateCarPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->date('date');
            $table->enum('type', PaymentType::getValues())
                ->default(PaymentType::Transaction);
            $table->foreignId('contract_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('contracts');
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
        Schema::dropIfExists('car_payments');
    }
}
