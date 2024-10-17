<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'updated_at',
    ];

    public function files(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function pictures(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function planPhotos(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function alt(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function description(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function city(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function characteristics(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function plans(): Attribute
    {
        return Attribute::make(
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
