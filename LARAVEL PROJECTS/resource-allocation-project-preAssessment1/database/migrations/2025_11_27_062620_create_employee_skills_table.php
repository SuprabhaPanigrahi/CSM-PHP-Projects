<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_employee_skills_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id('EmployeeSkillId');
            $table->foreignId('EmployeeId')->constrained('employees', 'EmployeeId');
            $table->foreignId('TechnologyId')->constrained('technologies', 'TechnologyId');
            $table->boolean('IsPrimarySkill')->default(false);
            $table->string('SkillLevel', 20)->nullable();
            $table->timestamps();
            
            $table->unique(['EmployeeId', 'TechnologyId']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_skills');
    }
};