@props(['type' => 'text', 'name' => false, 'options' => false, 'text' => null])

@php
    $allowed = [
        'color',
        'date',
        'email',
        'number',
        'password',
        'search',
        'tel',
        'text',
        'url',
    ];

    $values = [
        'class' => 'form-control',
    ];

    if ($name) {
        $values = array_merge($values, [
            'id' => $name,
            'name' => $name,
            'wire:model' => $name,            
        ]);
    
        $model = $attributes->get('wire:model');

        if (empty($model)) $model = $name;
    }
@endphp

@if (in_array($type, $allowed))
    <input {!! $attributes->merge($values + ['type' => $type]) !!}>

@elseif(($type == 'select') && $options)
    <select {!! $attributes->merge($values) !!}>
        @foreach ($options as $optionKey => $optionItem)
            <option value="{{ $optionKey }}">{{ $optionItem }}</option>
        @endforeach
    </select>

@elseif($type == 'textarea')
    <textarea {!! $attributes->merge($values) !!}>{{ $text }}</textarea>
@endif

@isset($model)
    <x-common.feedback-invalid :messages="$errors->get($model)" />
@endisset
