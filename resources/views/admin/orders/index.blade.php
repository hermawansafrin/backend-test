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
                                        <label for="min_total_amount" class="form-label">{{ __('general.min_total_amount') }}</label>
                                        <input type="number" id="min_total_amount" placeholder="{{ __('general.min_total_amount') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="max_total_amount" class="form-label">{{ __('general.max_total_amount') }}</label>
                                        <input type="number" id="max_total_amount" placeholder="{{ __('general.max_total_amount') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="min_created_at" class="form-label">{{ __('general.min_created_at') }}</label>
                                        <input type="date" id="min_created_at" placeholder="{{ __('general.min_created_at') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="max_created_at" class="form-label">{{ __('general.max_created_at') }}</label>
                                        <input type="date" id="max_created_at" placeholder="{{ __('general.max_created_at') }}.."
                                            class="form-control" />
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 my-1">
                                        <label for="status_flow_id" class="form-label">{{ __('general.status') }}</label>
                                        <select id="status_flow_id" class="form-control">
                                            <option value="">{{ __('general.all') }}</option>
                                            @foreach($status_flows as $status_flow)
                                                <option value="{{ $status_flow['id'] }}">{{ $status_flow['name'] }}</option>
                                            @endforeach
                                        </select>
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
                                            <th>{{ __($LANG_PATH.'index.table.uuid') }}</th>
                                            <th class="col-md-3">{{ __($LANG_PATH.'index.table.customer') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.total_amount') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.status_flow') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.paid_date_time') }}</th>
                                            <th>{{ __($LANG_PATH.'index.table.created_at') }}</th>
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

    <!-- Complete Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeModalLabel">{{ __('general.complete_confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="completeForm" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.transaction_code') }}:</label>
                            <p id="transactionCode" class=""></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.customer_name') }}:</label>
                            <p id="customerName" class=""></p>
                        </div>
                        <div class="mb-3">
                            <label for="paid_date_time" class="form-label">{{ __('general.paid_date_time') }}</label>
                            <input type="datetime-local" class="form-control" id="paid_date_time" name="paid_date_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('button.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('button.complete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                        keyword.min_total_amount = $('#min_total_amount').val();
                        keyword.max_total_amount = $('#max_total_amount').val();
                        keyword.min_created_at = $('#min_created_at').val();
                        keyword.max_created_at = $('#max_created_at').val();
                        keyword.status_flow_id = $('#status_flow_id').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'code', name: 'code' },
                    { data: 'customer', name: 'customer' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'status_flow', name: 'status_flow' },
                    { data: 'paid_date_time', name: 'paid_date_time' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#search_values').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#min_total_amount').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#max_total_amount').on('keyup', function(e) {
                if(e.which == 13) {
                    customDataTable.draw();
                    toastrSuccess(filterMsg);
                }
            });

            $('#status_flow_id').on('change', function() {
                customDataTable.draw();
                toastrSuccess(filterMsg);
            });

            $('#min_created_at').on('change', function() {
                customDataTable.draw();
                toastrSuccess(filterMsg);
            });

            $('#max_created_at').on('change', function() {
                customDataTable.draw();
                toastrSuccess(filterMsg);
            });

            // Complete Modal Handler
            $(document).on('click', '.complete-btn', function() {
                const id = $(this).data('id');
                const url = $(this).data('url');
                const code = $(this).data('code');
                const customerName = $(this).data('customer-name');
                $('#completeForm').attr('action', url);
                $('#transactionCode').text(code);
                $('#customerName').text(customerName);
                $('#completeModal').modal('show');
            });

        });

    </script>
@endpush
