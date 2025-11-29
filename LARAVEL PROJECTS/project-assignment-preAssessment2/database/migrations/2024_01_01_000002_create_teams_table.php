<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id('TeamId');
            $table->string('TeamCode', 20);
            $table->string('TeamName', 100);
            $table->foreignId('DepartmentId')->constrained('departments', 'DepartmentId');
            $table->enum('Status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            
            $table->unique(['DepartmentId', 'TeamName']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};