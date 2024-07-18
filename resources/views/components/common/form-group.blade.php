@props(['element' => false, 'label' => false])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    @if ($label)
        <label @if ($element) for="{{ $element }}" @endif>{{ $label }}</label>
    @endif
    {{ $slot }}
</div>
