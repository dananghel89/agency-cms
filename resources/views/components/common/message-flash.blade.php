@props(['on' => false])

@php
    $values = ['class' => 'message-flash'];
    $message = $slot->isEmpty() ? __('Saved.') : $slot;
@endphp

@if ($on)
    <div
        x-data="{ shown: false, timeout: null }"
        x-on:{{ $on }}.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
        x-show="shown"
        x-transition:leave.opacity.duration.1500ms
        style="display: none;"
        {{ $attributes->merge($values) }}
    >
        {{ $message }}
    </div>

@else
    <div
        x-data="{ shown: true }"
        x-show="shown"
        x-init="setTimeout(() => shown = false, 2000)"
        x-transition:leave.opacity.duration.1500ms
        style="display: none;"
        {{ $attributes->merge($values) }}
    >
        {{ $message }}
    </div>
@endif
