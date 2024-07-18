@props(['type' => 'default', 'on' => false, 'multiple' => false, 'message' => null])

@php
    if (empty($message)) {
        return;

    } elseif (is_array($message)) {
        $messages = $message;
    }

    $values = [
        'flash' => [
            'attr' => ['class' => 'card mb-4'],
            'class' => 'card-body py-3',
        ],
        'default' => [
            'attr' => ['class' => 'card mt-3'],
            'class' => 'card-body small py-2 px-3',
        ],
    ];
@endphp

@switch($type)
    @case('flash')
        <x-common.message-flash :on="$on">
            <div {{ $attributes->merge(data_get($values, 'flash.attr')) }}>
                <div class="{{ data_get($values, 'flash.class') }}">
                    {{ $message }}
                </div>
            </div>
        </x-common.message-flash>
        @break

    @default
        @if ($multiple && !empty($messages))
            <div {{ $attributes->merge(data_get($values, 'default.attr')) }}>
                <div class="{{ data_get($values, 'default.class') }}">
                    @foreach ((array) $messages as $messageItem)
                        <p class="m-0">{{ $messageItem }}</p>
                    @endforeach
                </div>
            </div>
            @break

        @else
            <div {{ $attributes->merge(data_get($values, 'default.attr')) }}>
                <div class="{{ data_get($values, 'default.class') }}">
                    {{ $message }}
                </div>
            </div>
        @endif
@endswitch
