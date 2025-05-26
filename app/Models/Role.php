<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
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
