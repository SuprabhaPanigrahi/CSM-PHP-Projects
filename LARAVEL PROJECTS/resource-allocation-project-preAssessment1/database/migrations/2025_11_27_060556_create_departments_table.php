<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('DepartmentId');
            $table->foreignId('BusinessUnitId')->constrained('business_units', 'BusinessUnitId');
            $table->string('Name', 100);
            $table->string('Code', 20);
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
            
            $table->unique(['BusinessUnitId', 'Code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};