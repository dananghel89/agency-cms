@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <div class="valid-feedback d-block">{{ $message }}</div>
    @endforeach
@endif
