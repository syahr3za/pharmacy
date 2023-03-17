<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name',64);
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('classification_id');
            $table->integer('price');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('classification_id')->references('id')->on('classifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
