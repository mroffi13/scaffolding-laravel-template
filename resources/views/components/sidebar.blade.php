<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ config('app.name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a
                href="index.html">{{ (!empty(config('app.name')[0]) ? config('app.name')[0] : '') . (!empty(config('app.name')[1]) ? config('app.name')[1] : '') }}</a>
        </div>
        <ul class="sidebar-menu">
            @foreach (\App\MenuFilter::getSidebarMenu() as $item)
                @if (!($item['restricted'] ?? false))
                    @if (!empty($item['header']))
                        <li class="menu-header">{{ $item['header'] }}</li>
                    @elseif(!empty($item['sub_menu']))
                        <li
                            class="nav-item dropdown {{ in_array(request()->route()->uri, $item['active']) ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i
                                    class="{{ $item['icon'] }}"></i><span>{{ $item['text'] }}</span></a>
                            <ul class="dropdown-menu">
                                @foreach ($item['sub_menu'] as $submenu)
                                    <li
                                        class='{{ in_array(request()->route()->uri, $submenu['active']) ? 'active' : '' }}'>
                                        <a class="nav-link"
                                            href="{{ route($submenu['route']) }}">{{ $submenu['text'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ in_array(request()->route()->uri, $item['active']) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route($item['route']) }}"><i class="{{ $item['icon'] }}"></i>
                                <span>{{ $item['text'] }}</span></a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </aside>
</div>
