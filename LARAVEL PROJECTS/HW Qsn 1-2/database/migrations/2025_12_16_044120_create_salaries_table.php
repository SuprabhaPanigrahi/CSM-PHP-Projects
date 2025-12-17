<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2);
            $table->decimal('deductions', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
};