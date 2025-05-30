<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'parent_id',
    'name',
    'slug',
    'position',
    'is_active',
    'is_sidebar',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  // Relasi untuk child menu
  public function children()
  {
    return $this->hasMany(Menu::class, 'parent_id');
  }

  public function parent()
  {
    return $this->belongsTo(Menu::class, 'parent_id');
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class, 'menu_roles');
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeSidebar($query)
  {
    return $query->where('is_sidebar', true);
  }

  public function scopeNavbar($query)
  {
    return $query->where('is_sidebar', false);
  }
}
