<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('short_description');
            $table->string('city');
            $table->unsignedInteger('year');
            $table->text('description');
            $table->text('files')->comment('[{"file", "alt":{"ru", "en"}}]');
            $table->string('image');
            $table->text('alt')->nullable()->comment('{"ru", "en"}');
            $table->text('pictures')->comment('[{"picture", "alt":{"ru", "en"}}]');
            $table->text('characteristics');
            $table->text('plans');
            $table->text('plan_photos')->comment('[{"plan_photo", "alt":{"ru", "en"}}]');
            $table->string('video');
            $table->string('address');
            $table->string('longitude');
            $table->string('latitude');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
