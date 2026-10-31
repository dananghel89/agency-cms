@section('title', __('Timeline'))
@section('slug', 'timeline')
@section('dataTables', true)

@section('action')
    <a href="{{ route('timeline.create') }}" class="btn btn-primary btn-sm">
        {{ __('Add New') }}
    </a>
@endsection

<x-admin-layout>

<!-- DataTales Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('All Timeline Items') }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Subtitle') }}</th>
                        <th>{{ __('Content') }}</th>
                        <th width="200">{{ __('Date') }}</th>
                        <th width="150" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $timeline)
                        <tr>
                            <td>{{ $timeline->title }}</td>
                            <td>{{ $timeline->subtitle }}</td>
                            <td>{{ Str::of($timeline->content)->limit(40, ' [...]') }}</td>
                            <td>{{ $timeline->created_at->format(config('project.date_format')) }}</td>
                            <td class="text-center">
                                @php
                                    $actions = [
                                        'edit' => route('timeline.edit', ['timeline' => $timeline->id]),
                                    ];
                                @endphp
                                <x-common.row-actions :item="$actions" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>
