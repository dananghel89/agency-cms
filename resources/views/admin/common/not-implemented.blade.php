@section('title', $title)
@section('slug', $slug)

<x-admin-layout>

<div class="card shadow mb-4">
    <div class="card-body">
        <p class="mb-4">{{ __('Not implemented yet.') }}</p>

        <a href="{{ $back }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i>
            {{ __('Back to the list') }}
        </a>
    </div>
</div>

</x-admin-layout>
