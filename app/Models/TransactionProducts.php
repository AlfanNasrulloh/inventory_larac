<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProducts extends Model
{
    //
    protected $fillable = ['quantity', 'price', 'sub_total'];
}
