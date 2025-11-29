<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_assignments', function (Blueprint $table) {
            $table->id('AssignmentId');
            $table->foreignId('ProjectId')->constrained('projects', 'ProjectId');
            $table->foreignId('EmployeeId')->constrained('employees', 'EmployeeId');
            $table->string('RoleOnProject', 100);
            $table->decimal('AllocationPercent', 5, 2);
            $table->date('StartDate');
            $table->date('EndDate');
            $table->enum('Status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });

        // Add CHECK constraints using raw SQL
        DB::statement("ALTER TABLE project_assignments
            ADD CONSTRAINT chk_allocation_percent
            CHECK (AllocationPercent > 0 AND AllocationPercent <= 100)");

        DB::statement("ALTER TABLE project_assignments
            ADD CONSTRAINT chk_dates
            CHECK (EndDate >= StartDate)");
    }

    public function down()
    {
        Schema::dropIfExists('project_assignments');
    }
};
