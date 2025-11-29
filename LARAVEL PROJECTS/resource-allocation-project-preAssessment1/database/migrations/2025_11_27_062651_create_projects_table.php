<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('ProjectId');
            $table->string('ProjectCode', 50)->unique();
            $table->string('ProjectName', 200);
            $table->string('ProjectType', 20); // Billable/Non-Billable
            $table->string('Priority', 20); // Normal/High
            $table->string('LocationType', 20); // Onsite/Offshore/Hybrid
            $table->foreignId('LocationCountryId')->nullable()->constrained('countries', 'CountryId');
            $table->foreignId('TechnologyId')->constrained('technologies', 'TechnologyId');
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};