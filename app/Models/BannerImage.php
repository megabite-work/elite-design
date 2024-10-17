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
            get: fn($data) => json_decode($data),
            set: fn($data) => json_encode($data, JSON_UNESCAPED_UNICODE),
        );
    }
}
