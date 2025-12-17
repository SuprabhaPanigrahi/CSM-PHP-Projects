<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country');
            $table->string('image_path')->nullable();
            $table->string('gender');
            $table->json('interests')->nullable();
            $table->text('message')->nullable();
            $table->boolean('terms_accepted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_submissions');
    }
};