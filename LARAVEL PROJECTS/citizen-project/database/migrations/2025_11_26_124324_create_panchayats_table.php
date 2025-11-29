<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('panchayats', function (Blueprint $table) {
            $table->id('PanchayatId');
            $table->unsignedBigInteger('BlockId');
            $table->string('PanchayatName', 100);
            $table->timestamps();

            $table->foreign('BlockId')
                  ->references('BlockId')
                  ->on('blocks')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('panchayats');
    }
};