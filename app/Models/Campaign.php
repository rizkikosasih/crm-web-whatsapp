<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'message', 'image', 'status', 'created_by'];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
