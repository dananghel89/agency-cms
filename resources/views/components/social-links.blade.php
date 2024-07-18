@props(['item'])

@if ($item)
    <div {{ $attributes->merge(['class' => 'social-links']) }}>
        @empty(!$item['facebook'])
        <a class="btn btn-dark btn-social mx-2" href="{{ $item['facebook'] }}" target="_blank" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        @endempty

        @empty(!$item['twitter'])
        <a class="btn btn-dark btn-social mx-2" href="{{ $item['twitter'] }}" target="_blank" aria-label="Twitter">
            <i class="fab fa-twitter"></i>
        </a>
        @endempty

        @empty(!$item['linkedin'])
        <a class="btn btn-dark btn-social mx-2" href="{{ $item['linkedin'] }}" target="_blank" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
        </a>
        @endempty
    </div>
@endif
