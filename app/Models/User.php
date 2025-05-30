<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  protected $fillable = [
    'id',
    'username',
    'name',
    'email',
    'phone',
    'avatar',
    'password',
    'role_id',
    'last_login_at',
    'last_login_ip',
  ];

  protected $hidden = ['password'];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    'last_login_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function role()
  {
    return $this->belongsTo(Role::class);
  }
}
