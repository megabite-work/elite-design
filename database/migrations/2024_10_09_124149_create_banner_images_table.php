<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('alt')->nullable()->comment('{"ru", "en"}');
            $table->text('images');
            $table->integer('sort');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_images');
    }
};
