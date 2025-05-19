<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  use HasFactory;

  protected $fillable = [
    'parent_id',
    'name',
    'slug',
    'position',
    'is_active',
    'is_sidebar',
    'is_delete',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  // Relasi untuk child menu
  public function children()
  {
    return $this->hasMany(Menu::class, 'parent_id');
  }

  // Relasi untuk parent menu
  public function parent()
  {
    return $this->belongsTo(Menu::class, 'parent_id');
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class, 'menu_roles');
  }
}
