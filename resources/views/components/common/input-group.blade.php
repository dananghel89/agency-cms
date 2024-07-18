@props(['type' => 'text', 'name' => false, 'options' => false, 'text' => null])

<div class="input-group mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text" @if ($name) id="{{ $name }}" @endif>
            {{ $slot->isEmpty() ? '@' : $slot }}
        </span>
    </div>
    <x-common.form-control :type="$type" :name="$name" :options="$options" :text="$text" {{ $attributes }} />
</div>
