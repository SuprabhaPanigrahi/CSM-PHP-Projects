<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_units', function (Blueprint $table) {
            $table->id('BusinessUnitId');
            $table->string('Name', 100);
            $table->string('Code', 20)->unique();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_units');
    }
};