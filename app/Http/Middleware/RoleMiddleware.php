<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\MenuRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    //Ambil role id user
    $role_id = Auth::user()->role_id;

    // Ambil route saat ini
    $slug = Route::currentRouteName();
    $explodeRoute = explode('-', $slug);
    $actions = ['add', 'create', 'edit', 'update', 'delete', 'active', 'detail'];
    $pattern = '/(' . implode('|', $actions) . ')/i';

    $slugParts = [];
    foreach ($explodeRoute as $segment) {
      if (preg_match($pattern, $segment)) {
        break;
      }
      $slugParts[] = $segment;
    }

    $slug = implode('-', $slugParts);

    // allowed custom slug
    $allowedCustomSlugs = ['index', 'login', 'logout', 'setting-user-profile'];
    if (in_array($slug, $allowedCustomSlugs)) {
      return $next($request);
    }

    // Ambil menu saat ini
    $menu = Menu::where('slug', $slug)->active()->firstOrFail();

    // Cek akses menu berdasarkan role di tabel menu_roles
    $hasAccess = MenuRole::where('role_id', $role_id)
      ->where('menu_id', $menu->id)
      ->exists();

    if (!$hasAccess) {
      return abort(403, 'Unauthorized');
    }

    return $next($request);
  }
}
