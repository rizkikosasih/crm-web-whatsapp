<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
  use HasFactory, SoftDeletes;

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected $fillable = ['title', 'message', 'image', 'image_url', 'created_by'];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
