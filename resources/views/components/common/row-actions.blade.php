@props(['item'])

@if ($item)
    <div {{ $attributes->merge(['class' => 'row-actions']) }}>
        @isset($item['view'])
            <a class="btn btn-circle btn-sm btn-primary" href="{{ $item['view'] }}">
                <i class="fas fa-eye"></i>
            </a>
        @endisset

        @isset($item['edit'])
            <a class="btn btn-circle btn-sm btn-primary" href="{{ $item['edit'] }}">
                <i class="fas fa-pencil-alt"></i>
            </a>
        @endisset

        @isset($item['delete'])
            <button type="button" class="btn btn-circle btn-sm btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        @endisset
    </div>
@endif
