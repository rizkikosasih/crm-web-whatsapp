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
    if (sizeof($explodeRoute) > 2) {
      if (preg_match($pattern, $explodeRoute[2])) {
        $slug = $explodeRoute[0] . '-' . $explodeRoute[1];
      }
    } elseif (sizeof($explodeRoute) > 1) {
      if (preg_match($pattern, $explodeRoute[1])) {
        $slug = $explodeRoute[0];
      }
    }

    // Ambil menu saat ini
    $menu = Menu::where('slug', $slug)->first();
    if (!$menu) {
      return abort(404);
    }

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
