<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id('VillageId');
            $table->unsignedBigInteger('PanchayatId');
            $table->string('VillageName', 100);
            $table->timestamps();

            $table->foreign('PanchayatId')
                  ->references('PanchayatId')
                  ->on('panchayats')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('villages');
    }
};