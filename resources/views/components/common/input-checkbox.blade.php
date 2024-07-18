@props(['name'])

@php
    $values = [
        'class' => 'custom-control-input',
        'id' => $name,
        'name' => $name,
        'wire:model' => $name,
    ];

    $model = $attributes->get('wire:model');

    if (empty($model)) $model = $name;
@endphp

<div class="col-form-label">

<div class="custom-control custom-checkbox">
    <input type="checkbox" {!! $attributes->merge($values) !!}>
    <label class="custom-control-label" for="{{ $name }}">{{ $slot }}</label>
</div>
<x-common.feedback-invalid :messages="$errors->get($model)" />

</div>
