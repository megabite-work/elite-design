<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function files(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data, true),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function pictures(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data, true),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function planPhotos(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data, true),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
