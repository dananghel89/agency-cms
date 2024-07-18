@section('title', __('Portfolio'))
@section('slug', 'portfolio')
@section('dataTables', true)

@section('action')
    <a href="{{ route('portfolio.create') }}" class="btn btn-primary btn-sm">
        {{ __('Add New') }}
    </a>
@endsection

<x-admin-layout>

<!-- DataTales Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('All Contacts') }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Subtitle') }}</th>
                        <th>{{ __('Content') }}</th>
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th width="200">{{ __('Date') }}</th>
                        <th width="150" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $portfolio)
                        <tr>
                            <td>{{ $portfolio->title }}</td>
                            <td>{{ $portfolio->subtitle }}</td>
                            <td>{{ Str::of($portfolio->content)->limit(40, ' [...]') }}</td>
                            <td>{{ $portfolio->client }}</td>
                            <td>{{ $portfolio->category }}</td>
                            <td>{{ $portfolio->created_at->format(config('sb.date_format')) }}</td>
                            <td class="text-center">
                                @php
                                    $actions = [
                                        'edit' => route('portfolio.edit', ['portfolio' => $portfolio->id]),
                                        'delete' => 'deletePortfolio',
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
