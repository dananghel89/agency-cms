@props(['element' => false, 'label' => false])

<div {{ $attributes->merge(['class' => 'form-group row']) }}>
    @if ($label)
        <label @if ($element) for="{{ $element }}" @endif class="col-sm-3 col-form-label">{{ $label }}</label>
    @endif
    <div class="col-sm-9">
        {{ $slot }}
    </div>
</div>
