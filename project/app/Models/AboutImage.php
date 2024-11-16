<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'alt' => 'array',
        'title' => 'array',
        'description' => 'array',
    ];

    protected $hidden = [
        'updated_at',
    ];

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
}
