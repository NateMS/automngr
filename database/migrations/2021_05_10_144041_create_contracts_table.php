<?php

use App\Enums\ContractType;
use App\Enums\InsuranceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('price');
            $table->date('delivery_date');
            $table->text('notes')->nullable();
            $table->foreignId('contact_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('contacts');
            $table->foreignId('car_id')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->constrained('cars');
            $table->enum('insurance_type', InsuranceType::getValues())
            ->default(InsuranceType::Keine);
            $table->enum('type', ContractType::getValues())
            ->default(ContractType::SellContract);
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
        Schema::dropIfExists('contracts');
    }
}
