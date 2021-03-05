@php
$current_route = Route::currentRouteName();
@endphp
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion bloggle-navbar-nav" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a
        href="{{ route('admin.index') }}"
        class="sidebar-brand d-flex align-items-center justify-content-center bloggle-sidebar-brand">
        <div class="sidebar-brand-icon">
            <img
                width="125"
                src="{{ asset('backend/img/new-logo.png') }}"
                alt="Bloggle" />
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @role(['admin'])
        @foreach($admin_sidebar_menu as $menu)
        @if (count($menu->visibleChildren) == 0)
            <li class="nav-item {{ $menu->id == get_parent_show_of($current_route) ? 'active' : '' }}">
                <a href="{{ route('admin.'. $menu->as) }}" class="nav-link">
                    <i class="{{ $menu->icon != null ? $menu->icon : 'fas fa-home' }}"></i>
                    <span>{{ $menu->display_name }}</span></a>
            </li>
            <hr class="sidebar-divider" />
        @else
        <li
            class="nav-item {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'active' : '' }}">
            <a class="nav-link {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'collapsed' : '' }}"
                href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_{{ $menu->route }}"
                aria-expanded="{{ $menu->parent_show == get_parent_of($current_route) && get_parent_of($current_route) != null ? 'false' : 'true' }}"
                aria-controls="collapse_{{ $menu->route }}">
                <i class="{{ $menu->icon != null ? $menu->icon : 'fa fa-home' }}"></i>
                <span>{{ $menu->display_name }}</span>
            </a>
            @if (isset($menu->visibleChildren) && count($menu->visibleChildren) > 0)
            <div id="collapse_{{ $menu->route }}"
                class="collapse {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'show' : '' }}"
                aria-labelledby="heading_{{ $menu->route }}" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @foreach($menu->visibleChildren as $sub_menu)
                    <a class="collapse-item {{ get_parent_of($current_route) != null && (int)(get_parent_id_of($current_route)+1) == $sub_menu->id ? 'active' : '' }}"
                        href="{{ route('admin.' . $sub_menu->as) }}">{{ $sub_menu->display_name }}</a>
                    @endforeach
                </div>
            </div>
            @endif
        </li>
        @endif
        @endforeach
    @endrole

    @role(['editor'])
    @foreach($admin_sidebar_menu as $menu)
    @permission($menu->name)
    @if (count($menu->visibleChildren) == 0)
    <li class="nav-item {{ $menu->id == get_parent_show_of($current_route) ? 'active' : '' }}">
        <a href="{{ route('admin.'. $menu->as) }}" class="nav-link">
            <i class="{{ $menu->icon != null ? $menu->icon : 'fa fa-home' }}"></i>
            <span>{{ $menu->display_name }}</span></a>
    </li>
    <hr class="sidebar-divider">
    @else
    <li
        class="nav-item {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'active' : '' }}">
        <a class="nav-link {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'collapsed' : '' }}"
            href="#" data-toggle="collapse" data-target="#collapse_{{ $menu->route }}"
            aria-expanded="{{ $menu->parent_show == get_parent_of($current_route) && get_parent_of($current_route) != null ? 'false' : 'true' }}"
            aria-controls="collapse_{{ $menu->route }}">
            <i class="{{ $menu->icon != null ? $menu->icon : 'fa fa-home' }}"></i>
            <span>{{ $menu->display_name }}</span>
        </a>
        @if (isset($menu->visibleChildren) && count($menu->visibleChildren) > 0)
        <div id="collapse_{{ $menu->route }}"
            class="collapse {{ in_array($menu->parent_show, [get_parent_show_of($current_route), get_parent_of($current_route)]) ? 'show' : '' }}"
            aria-labelledby="heading_{{ $menu->route }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @foreach($menu->visibleChildren as $sub_menu)
                @permission($sub_menu->name)
                <a class="collapse-item {{ get_parent_of($current_route) != null && (int)(get_parent_id_of($current_route)+1) == $sub_menu->id ? 'active' : '' }}"
                    href="{{ route('admin.' . $sub_menu->as) }}">{{ $sub_menu->display_name }}</a>
                @endpermission
                @endforeach
            </div>
        </div>
        @endif
    </li>
    @endif
    @endpermission
    @endforeach
    @endrole



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
