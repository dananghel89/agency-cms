@props(['item'])

@php
    $links = App\Models\Options::socialNetworks()
        ->map(fn ($network) => $network + ['link' => $item[$network['name']] ?? null])
        ->filter(fn ($network) => is_string($network['link']) && preg_match('#^https?://#i', $network['link']));
@endphp

@if ($links->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'social-links']) }}>
        @foreach ($links as $network)
            <a class="btn btn-dark btn-social mx-2" href="{{ $network['link'] }}" target="_blank" rel="noopener" aria-label="{{ $network['label'] }}">
                <i class="fab {{ $network['icon'] }}"></i>
            </a>
        @endforeach
    </div>
@endif
