<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
  use HasFactory, SoftDeletes;

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
