<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'about' => 'array',
        'images' => 'array',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function images(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function about(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
