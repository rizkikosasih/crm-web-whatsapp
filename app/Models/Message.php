<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
  use HasFactory;

  protected $fillable = [
    'customer_id',
    'user_id',
    'message',
    'image',
    'status',
    'sent_at',
  ];

  protected $casts = [
    'sent_at' => 'datetime',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
