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
            $table->string('title')->comment('{"ru", "en"}');
            $table->text('short_description')->comment('{"ru", "en"}');
            $table->string('city')->comment('{"ru", "en"}');
            $table->unsignedInteger('year');
            $table->text('description')->comment('{"ru", "en"}');
            $table->text('files')->comment('[{"file", "alt":{"ru", "en"}}]');
            $table->string('image');
            $table->text('alt')->nullable()->comment('{"ru", "en"}');
            $table->text('pictures')->comment('[{"picture", "alt":{"ru", "en"}}]');
            $table->text('characteristics')->comment('{"ru", "en"}');
            $table->text('plans')->comment('{"ru", "en"}');
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
