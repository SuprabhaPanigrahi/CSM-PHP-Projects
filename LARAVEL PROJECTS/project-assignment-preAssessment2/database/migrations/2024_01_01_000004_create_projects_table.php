<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('ProjectId');
            $table->string('ProjectCode', 30)->unique();
            $table->string('ProjectName', 150);
            $table->foreignId('TeamId')->constrained('teams', 'TeamId');
            $table->boolean('IsBillable')->default(false);
            $table->enum('Status', ['Active', 'Inactive'])->default('Active');
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};