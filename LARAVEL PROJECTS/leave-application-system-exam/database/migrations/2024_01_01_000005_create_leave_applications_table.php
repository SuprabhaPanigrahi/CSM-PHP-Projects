<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id('LeaveApplicationId');
            $table->foreignId('EmployeeId')->constrained('employees', 'EmployeeId');
            $table->foreignId('LeaveTypeId')->constrained('leave_types', 'LeaveTypeId');
            $table->date('FromDate');
            $table->date('ToDate');
            $table->decimal('TotalDays', 5, 2);
            $table->text('Reason');
            $table->string('Status', 20)->default('Pending');
            $table->timestamp('AppliedOn')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_applications');
    }
};