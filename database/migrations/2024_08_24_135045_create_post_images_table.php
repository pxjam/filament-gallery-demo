<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->json('title');
            $table->string('filename');
            $table->integer('size');
            $table->integer('order_column');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};
