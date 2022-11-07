@extends('layouts.base')
@section('title', $title)

@section('js')
    <script src="https://aws.btekno.id/templates/front-dashboard/2.1/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="https://aws.btekno.id/templates/front-dashboard/2.1/vendor/datatables.net.extensions/select/select.min.js"></script>
    <script data-exec-on-popstate>
        var table = Nue.components.NueDatatables.init('.js-datatable', {
            scrollY: 'calc(100vh - 199px)',
            ajax : '{!! request()->fullUrl() !!}?datatable=true', 
            columns: [
                { data: 'pilihan', name: 'pilihan', className: 'pe-0 bg-light text-center', orderable: false, searchable: false },
                { data: 'id', name: 'id', className: 'text-center' },
                { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false }, 
                { data: 'uid', name: 'uid' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles', name: 'roles', orderable: false, searchable: false },
                { data: 'last_login_at', name: 'last_login_at', orderable: false, searchable: false },
            ],
            @include('nue::partials.datatable.script')
        });
    </script>
@endsection

@section('content')
    
    @include('nue::partials.breadcrumb', ['lists' => [
        __('Extensions') => 'javascript:;', 
        $title => 'active'
    ]])

    @include('nue::partials.toolbar', [
        'create' => route("$prefix.create"), 
        'datatable' => true
    ])

    {!! Form::open(['method' => 'DELETE', 'route' => ["$prefix.destroy", 'hapus-all'], 'id' => 'submit-all']) !!}

    	<div class="card border-0 shadow-none rounded-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable" class="js-datatable table table-sm table-hover table-bordered table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th class="pe-0" width="1">
                                    <div class="form-check mb-0">
                                        <input id="datatable-checkbox-check" type="checkbox" class="form-check-input">
                                        <label class="form-check-label" for="check-all"></label>
                                    </div>
                                </th>
                                <th width="1">ID</th>
                                <th width="1"></th>
                                <th>{{ __('SSO ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Roles') }}</th>
                                <th>{{ __('Last Login') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            @include('nue::partials.datatable.footer')
        </div>

    {!! Form::close() !!}

@endsection