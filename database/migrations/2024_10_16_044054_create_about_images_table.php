<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('{"ru", "en"}');
            $table->string('image');
            $table->text('description')->comment('{"ru", "en"}');
            $table->text('alt')->nullable()->comment('{"ru", "en"}');
            $table->integer('sort');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_images');
    }
};
