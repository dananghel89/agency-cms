@php
    if (empty($sidebarNav)) return;

    $slug = data_get(app()->view->getSections(), 'slug');
@endphp

<!-- Sidebar -->
<ul {{ $attributes->merge(['id' => 'accordionSidebar', 'class' => 'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion']) }}>
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15 d-none">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('project.admin_title') }}</div>
    </a>
    <hr class="sidebar-divider my-0">
    @foreach ($sidebarNav as $item)
        @php
            $name = !empty($item['route']) ? $item['route'] : $item['slug'];
            $link = route($name);
        @endphp
        @if ($item['group'] && (empty($prevGroup) || $prevGroup !== $item['group']))
            <hr class="sidebar-divider">
            <div class="sidebar-heading">{{ __($item['group']) }}</div>
        @endif
        <li class="nav-item @if ($slug == $item['slug']) active @endif">
            <a class="nav-link" href="{{ $link }}">
                <i class="fas fa-fw fa-{{ $item['icon'] }}"></i>
                <span>{{ __($item['label']) }}</span>
            </a>
        </li>
        @php
            $prevGroup = $item['group'];
        @endphp
    @endforeach
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
