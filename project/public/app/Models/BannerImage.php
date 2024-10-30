<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerImage extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'alt' => 'array',
        'images' => 'array',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }

    public function alt(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }

    public function images(): Attribute
    {
        return Attribute::make(
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
