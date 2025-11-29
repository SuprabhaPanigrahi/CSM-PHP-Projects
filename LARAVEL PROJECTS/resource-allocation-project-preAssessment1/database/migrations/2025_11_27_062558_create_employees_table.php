<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_employees_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('EmployeeId');
            $table->string('EmployeeCode', 20)->unique();
            $table->string('FullName', 150);
            $table->foreignId('TeamId')->constrained('teams', 'TeamId');
            $table->foreignId('EmployeeStatusId')->constrained('employee_status', 'EmployeeStatusId');
            $table->decimal('YearsOfExperience', 4, 1);
            $table->foreignId('WorkLocationCountryId')->nullable()->constrained('countries', 'CountryId');
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};