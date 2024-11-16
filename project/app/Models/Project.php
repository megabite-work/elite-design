<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'files' => 'array',
        'pictures' => 'array',
        'plan_photos' => 'array',
        'alt' => 'array',
        'title' => 'array',
        'description' => 'array',
        'city' => 'array',
        'short_description' => 'array',
        'characteristics' => 'array',
        'plans' => 'array',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function files(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function pictures(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function planPhotos(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function alt(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function title(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function description(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function city(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function shortDescription(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function characteristics(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function plans(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
