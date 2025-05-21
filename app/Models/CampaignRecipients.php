<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignRecipient extends Model
{
  use HasFactory;

  protected $fillable = ['campaign_id', 'customer_id', 'status', 'sent_at'];

  protected $casts = [
    'sent_at' => 'datetime',
  ];

  public function campaign()
  {
    return $this->belongsTo(Campaign::class);
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
}
