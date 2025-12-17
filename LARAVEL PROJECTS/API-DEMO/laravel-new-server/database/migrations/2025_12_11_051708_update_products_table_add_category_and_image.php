<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add category_id foreign key
            $table->foreignId('category_id')->nullable()->after('price')
                  ->constrained()->onDelete('set null');
            
            // Add description column
            $table->text('description')->nullable()->after('category_id');
            
            // Add image column
            $table->string('image')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'description', 'image']);
        });
    }
};