<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->text('about');
            $table->text('images')->comment('[{"image", "alt":{"ru", "en"}}]');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_settings');
    }
};
