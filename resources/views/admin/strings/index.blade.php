@section('title', __('Strings'))
@section('slug', 'strings')
@section('dataTables', true)

@section('action')
    <a href="{{ route('strings.create') }}" class="btn btn-primary btn-sm">
        {{ __('Add New') }}
    </a>
@endsection

<x-admin-layout>

<x-common.message-success type="flash" :message="session('status')" />

<!-- DataTales Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('All Strings') }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Key') }}</th>
                        <th>{{ __('Replace') }}</th>
                        <th width="200">{{ __('Date') }}</th>
                        <th width="150" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $string)
                        <tr>
                            <td>{{ $string->key }}</td>
                            <td>{{ $string->replace }}</td>
                            <td>{{ $string->created_at->format(config('sb.date_format')) }}</td>
                            <td class="text-center">
                                @php
                                    $actions = [
                                        'edit' => route('strings.edit', ['string' => $string->id]),
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
