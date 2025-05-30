<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name'];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  public function users()
  {
    return $this->belongsToMany(User::class, 'users');
  }

  public function menus()
  {
    return $this->belongsToMany(Menu::class, 'menu_roles');
  }

  public function menuRoles()
  {
    return $this->hasMany(MenuRole::class);
  }
}
