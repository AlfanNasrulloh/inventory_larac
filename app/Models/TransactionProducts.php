<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionProducts extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['quantity', 'price', 'sub_total', 'product_id', 'transaction_id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
