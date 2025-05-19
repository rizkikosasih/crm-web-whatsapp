<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/admin') }}" class="brand-link">
    <img src="{{ asset('storage/images/logo.jpg') }}" alt="Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">CMS Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($menus as $menu)
          <li class="nav-header">{{$menu->name}}</li>
          @foreach ($menu->children as $child)
            <x-sidebar-menu
              activeClass="{{ setActive($child->slug) }}"
              parentClass="{{ parentClass($child) }}"
              name="{{ $child->name }}"
              route="{{ $child->route }}"
              icon="{{ $child->icon }}"
            >
              @if($child->children)
                <ul class="nav nav-treeview">
                  @foreach($child->children as $subChild)
                    <x-sidebar-menu
                      activeClass="{{ setActive($subChild->slug) }}"
                      parentClass="{{ parentClass($subChild) }}"
                      name="{{ $subChild->name }}"
                      route="{{ $subChild->route }}"
                      icon="{{ $subChild->icon }}"
                    ></x-sidebar-menu>
                  @endforeach
                </ul>
              @endif
            </x-sidebar-menu>
          @endforeach
        @endforeach
    </nav>
  </div>
</aside>
