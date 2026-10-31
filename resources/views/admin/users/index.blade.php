@section('title', __('Users'))
@section('slug', 'users')
@section('dataTables', true)

@section('action')
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        {{ __('Add New') }}
    </a>
@endsection

<x-admin-layout>

<!-- DataTales Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-none">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('All Users') }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email Address') }}</th>
                        <th width="200">{{ __('Date') }}</th>
                        <th width="150" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format(config('project.date_format')) }}</td>
                            <td class="text-center">
                                @php
                                    $actions = [
                                        'edit' => route('users.edit', ['user' => $user->id]),
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
