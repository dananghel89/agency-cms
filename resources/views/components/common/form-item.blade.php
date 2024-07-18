@props(['type' => 'text', 'name' => false, 'options' => false, 'text' => null])

<x-common.form-row :element="$name" :label="$slot">
    <x-common.form-control :type="$type" :name="$name" :options="$options" :text="$text" {{ $attributes }} />
</x-common.form-row>
