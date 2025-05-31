@extends('admin.__layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">{{ $subtitle ?? '' }}</h3>
                            <div class="card-toolbar float-right">
                                <a href="{{ route($ROUTE_PATH.'create') }}" class="btn btn-sm btn-primary">
                                    {{ __('button.add') }}
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <input id="getEndpoint" value="{{ route($ROUTE_PATH.'getYajra') }}" type="hidden" />
                        <input type="hidden" id="filterMsg" value="{{ __('messages.filter_implemented') }}" />

                        <div class="card-body">
                            @include('admin.__components.alert_session')

                            <div class="col-12">
                                <div class="row">

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="min_stock" class="form-label">{{ __('general.min_stock') }}</label>
                                        <input type="number" id="min_stock" placeholder="{{ __('general.min_stock') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="max_stock" class="form-label">{{ __('general.max_stock') }}</label>
                                        <input type="number" id="max_stock" placeholder="{{ __('general.max_stock') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="min_price" class="form-label">{{ __('general.min_price') }}</label>
                                        <input type="number" id="min_price" placeholder="{{ __('general.min_price') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="max_price" class="form-label">{{ __('general.max_price') }}</label>
                                        <input type="number" id="max_price" placeholder="{{ __('general.max_price') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="search_values" class="form-label">{{ __('general.search') }}</label>
                                        <input type="text" id="search_values" placeholder="{{ __('general.search') }}.."
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive col-12 mt-3">
                                <table class="table table-bordered" style="width: 100%;" id="myTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>{{ __($LANG_PATH.'index.table.name') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.price') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.stock') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.is_active') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('before_styles')
    @include('admin.__components.init_datatable_up')
    @include('admin.__components.init_toastr_up')
@endpush

@push('before_scripts')
    @include('admin.__components.init_datatable_down')
    @include('admin.__components.init_toastr_down')
    <script>

        $(function(){
            const filterMsg = $('#filterMsg').val();

            let customDataTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: $('#getEndpoint').val(),
                    data: function(keyword) {
                        keyword.search_values = $('#search_values').val();
                        keyword.min_stock = $('#min_stock').val();
                        keyword.max_stock = $('#max_stock').val();
                        keyword.min_price = $('#min_price').val();
                        keyword.max_price = $('#max_price').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'stock', name: 'stock' },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#search_values').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#min_stock').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#max_stock').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#min_price').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#max_price').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

        });

    </script>
@endpush