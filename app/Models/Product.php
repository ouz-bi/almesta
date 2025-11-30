<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'reference',
        'description',
        'short_description',
        'price',
        'compare_price',
        'stock',
        'stock_quantity', // Garder les deux pour compatibilité
        'track_stock',
        'is_active',
        'is_featured',
        'images',
        'main_image_id',
        'has_variants',
        'variants',
        'gender',
        'category_id',
        'sort_order',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_stock' => 'boolean',
        'has_variants' => 'boolean',
        'images' => 'array',
        'variants' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function availableVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true)->where('stock_quantity', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getMainImageAttribute()
    {
        if ($this->main_image_id && $this->images) {
            foreach ($this->images as $image) {
                if ($image['id'] === $this->main_image_id) {
                    return $image;
                }
            }
        }
        
        // Retourner la première image si pas d'image principale définie
        return $this->images && count($this->images) > 0 ? $this->images[0] : null;
    }

    public function getTotalStockAttribute()
    {
        if ($this->has_variants) {
            return $this->productVariants()->sum('stock_quantity');
        }
        
        return $this->stock_quantity;
    }

    public function getAvailableSizesAttribute()
    {
        return $this->productVariants()
            ->with('size')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get()
            ->pluck('size')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order');
    }

    public function getAvailableColorsAttribute()
    {
        return $this->productVariants()
            ->with('color')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get()
            ->pluck('color')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order');
    }
}
