<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id(); // primary key, auto-incrementing
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};