<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">
    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        @if (!Auth::user()->avatar)
          <i class="fas fa-user-circle"></i>
        @else
          <img src="{!! imageUri(Auth::user()->avatar) !!}" class="avatar-image-navbar img-fluid img-circle"/>
        @endif
        <span class="text-sm">{{ Auth::user()->name }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ url('setting/user/profile') }}" class="dropdown-item" wire:navigate>
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>
    </li>
  </ul>
</nav>
