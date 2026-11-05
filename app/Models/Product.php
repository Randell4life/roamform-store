<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
class Product extends Model {
 protected $fillable = ['category_id','name','slug','sku','short_description','description','price','compare_at_price','image_url','image_path','supplier','supplier_product_id','supplier_url','stock_quantity','low_stock_threshold','is_active'];
 protected function casts(): array { return ['price'=>'decimal:2','compare_at_price'=>'decimal:2','is_active'=>'boolean','stock_quantity'=>'integer','low_stock_threshold'=>'integer']; }
 public function category(): BelongsTo { return $this->belongsTo(Category::class); }
 public function scopeActive(Builder $query): Builder { return $query->where('is_active',true); }
 public function getImageAttribute(): ?string { return $this->image_path ? Storage::disk('public')->url($this->image_path) : $this->image_url; }
 public function getIsLowStockAttribute(): bool { return $this->stock_quantity <= $this->low_stock_threshold; }
}