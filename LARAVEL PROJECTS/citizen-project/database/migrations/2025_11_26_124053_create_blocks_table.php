<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id('BlockId');
            $table->unsignedBigInteger('StateId');
            $table->string('BlockName', 100);
            $table->timestamps();

            $table->foreign('StateId')
                  ->references('StateId')
                  ->on('states')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blocks');
    }
};