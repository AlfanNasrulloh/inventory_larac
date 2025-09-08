<?php

namespace App\Models;

use App\Models\Merchant;
use App\Models\Categorie;
use App\Models\Warehouse;
use App\Models\TransactionProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'thumbnail', 'about', 'price', 'is_popular', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_products')
            ->withPivot('stock') //ketika melakukan CRUD -> nanti akan mendapatkan stock di API, jika tidak menggunakn ini maka akan langsung fetching dari merchant
            ->withTimeStamps();
    }

    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    public function transaction()
    {
        return $this->hasMany(TransactionProducts::class);
    }

    public function getWarehouseProductStock()
    {
        return $this->warehouse()->sum('stock');
    }

    public function getMerchantProductStock()
    {
        return $this->merchants()->sum('stock');
    }

    public function getThumbnailAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(Storage::url($value));
    }
}
