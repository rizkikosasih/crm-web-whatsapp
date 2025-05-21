<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'message', 'status', 'schedule_at', 'created_by'];

  protected $casts = [
    'schedule_at' => 'datetime',
  ];

  public function recipients()
  {
    return $this->hasMany(CampaignRecipient::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
