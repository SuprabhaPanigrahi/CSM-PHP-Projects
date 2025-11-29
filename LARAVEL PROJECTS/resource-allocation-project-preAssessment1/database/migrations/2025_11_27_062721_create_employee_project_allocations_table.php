<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_project_allocations', function (Blueprint $table) {
            $table->id('AllocationId');
            $table->foreignId('EmployeeId')->constrained('employees', 'EmployeeId');
            $table->foreignId('ProjectId')->constrained('projects', 'ProjectId');
            $table->date('AllocationStartDate');
            $table->date('AllocationEndDate')->nullable();
            $table->integer('AllocationPercentage')->default(100);
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_project_allocations');
    }
};