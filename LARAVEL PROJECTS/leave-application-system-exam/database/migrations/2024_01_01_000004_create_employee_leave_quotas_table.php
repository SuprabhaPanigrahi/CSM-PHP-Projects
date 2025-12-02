<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_leave_quotas', function (Blueprint $table) {
            $table->id('QuotaId');
            $table->foreignId('EmployeeId')->constrained('employees', 'EmployeeId');
            $table->foreignId('LeaveTypeId')->constrained('leave_types', 'LeaveTypeId');
            $table->integer('LeaveYear');
            $table->decimal('TotalAllocated', 5, 2);
            $table->decimal('TotalUsed', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['EmployeeId', 'LeaveTypeId', 'LeaveYear']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_leave_quotas');
    }
};