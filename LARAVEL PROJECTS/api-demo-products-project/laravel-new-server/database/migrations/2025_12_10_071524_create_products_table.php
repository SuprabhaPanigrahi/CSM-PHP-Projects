<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->string('category'); // dropdown
            $table->string('status')->default('active'); // radio
            $table->json('features')->nullable(); // checkboxes
            $table->string('color')->nullable(); // color picker
            $table->date('manufacturing_date')->nullable();
            $table->string('image')->nullable();
            $table->string('gallery_images')->nullable(); // JSON array for multiple images
            $table->boolean('is_featured')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

?>