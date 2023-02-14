<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                {{-- @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item') --}}
                
                {{-- Sidebar --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if(Route::current()->getName() == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-list') }}" class="nav-link @if(Route::current()->getName() == 'user-list') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Management
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview @if(Route::current()->getName() == 'purchase-order-list' || Route::current()->getName() == 'purchase-order-create') menu-open @endif">
                    <a href="#" class="nav-link @if(Route::current()->getName() == 'purchase-order-list' || Route::current()->getName() == 'purchase-order-create') active @endif">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Purchase Order
                        <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('purchase-order-list') }}" class="nav-link @if(Route::current()->getName() == 'purchase-order-list') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase-order-create') }}" class="nav-link @if(Route::current()->getName() == 'purchase-order-create') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

</aside>
