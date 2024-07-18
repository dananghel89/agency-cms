@section('title', __('Dashboard'))
@section('slug', 'dashboard')

<x-admin-layout>

@php
    if (empty($sidebarNav)) return;
@endphp

<!-- Content Row -->
<div class="row">
    @foreach ($sidebarNav as $item)
        @php
            if (!$featured->has($item['slug'])) {
                continue;
            }

            $name = !empty($item['route']) ? $item['route'] : $item['slug'];
            $link = route($name);
        @endphp
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <a href="{{ $link }}">{{ $item['label'] }}</a>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $featured->get($item['slug']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $item['icon'] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

</x-admin-layout>
