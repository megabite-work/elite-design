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
            $table->text('files');
            $table->string('image');
            $table->text('pictures');
            $table->text('characteristics');
            $table->text('plans');
            $table->text('plan_photos');
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
