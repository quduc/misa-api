<?php

namespace App\Models;

use App\Traits\HasModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, HasModel;
    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'unit',
        'price',
        'description',
        'discount',
        'is_available',
        'is_active',
        'inventory_number',
        'out_of_stock_date',
        'name'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];
    protected $casts = [
        'category' => 'string',
    ];
    protected $appends = [
        'category',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    public function images(): HasMany
    {
        return $this->hasMany(\App\Models\ProductMedia::class, 'product_id');
    }

    public function categories(): HasOne
    {
        return $this->hasOne(\App\Models\Category::class, 'category_id');
    }

    public function favorite()
    {
        return $this->hasOne(\App\Models\ProductFavorite::class, 'product_id');
    }



    public function getCategoryAttribute()
    {
        if (empty($this->category_id)) return null;
        return Category::where('id', $this->category_id)
            ->first();
    }
}
