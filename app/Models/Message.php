<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
  use HasFactory, SoftDeletes;

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
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected static function booted()
  {
    static::creating(function ($message) {
      if (is_null($message->sent_at)) {
        $message->sent_at = now();
      }
    });
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
