<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsappApiSetting extends Model
{
  use HasFactory;
  protected $table = 'whatsapp_settings';

  protected $fillable = ['key', 'url'];
}
