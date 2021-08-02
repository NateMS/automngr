<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 75)->nullable();
            $table->string('lastname', 75)->nullable();
            $table->string('phone', 75)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('zip', 6)->nullable();
            $table->string('city', 75)->nullable();
            $table->string('country', 3)->nullable();
            $table->string('company', 75)->nullable();
            $table->string('email', 75)->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
