<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')
                ->constrained('visits')
                ->cascadeOnDelete();

            $table->string('original_file_name');
            $table->string('stored_file_name')->unique();
            $table->string('file_path');

            $table->unsignedBigInteger('file_size');
            $table->enum('file_type', ['pdf', 'jpg', 'png']);

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('uploaded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
