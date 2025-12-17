<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category; // Import the Category model

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert some default categories using Eloquent
        Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Books']);
        Category::create(['name' => 'Home & Garden']);
        Category::create(['name' => 'Sports']);
        
        // OR using insert with timestamps
        Category::insert([
            ['name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clothing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Books', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Home & Garden', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // OR using a loop with Eloquent
        $categories = ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports'];
        
        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }
    }

    public function down(): void
    {
        // Delete categories using Eloquent before dropping table
        Category::whereIn('name', ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports'])->delete();
        
        Schema::dropIfExists('categories');
    }
};