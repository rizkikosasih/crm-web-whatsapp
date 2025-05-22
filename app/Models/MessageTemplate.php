<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageTemplate extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'body', 'image', 'type', 'is_active'];
}
