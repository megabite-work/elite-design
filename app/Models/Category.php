<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function bannerImages(): HasMany
    {
        return $this->hasMany(BannerImage::class, "category_id", "id");
    }
}
