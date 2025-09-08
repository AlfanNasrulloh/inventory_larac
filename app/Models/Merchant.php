<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'address', 'photo', 'phone'];

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return url(Storage::url($value));
    }
}
