<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->id('CitizenId');
            $table->unsignedBigInteger('VillageId');
            $table->string('CitizenName', 100);
            $table->enum('CitizenGender', ['Male', 'Female', 'Other']);
            $table->string('CitizenPhone', 15);
            $table->string('CitizenEmail', 100)->nullable();
            $table->timestamps();

            $table->foreign('VillageId')
                  ->references('VillageId')
                  ->on('villages')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('citizens');
    }
};