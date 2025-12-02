<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('EmployeeId');
            $table->string('EmployeeCode', 50)->unique();
            $table->string('FirstName', 100);
            $table->string('LastName', 100)->nullable();
            $table->foreignId('DepartmentId')->constrained('departments', 'DepartmentId');
            $table->date('DateOfJoining')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};