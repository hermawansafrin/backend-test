@inject('formatter', 'App\Helpers\FormatterHelper')

<div class="row">
    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __($LANG_PATH.'info.title_customer') }}
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <dl>
                        <dt>{{ __($LANG_PATH.'info.customer.name') }}</dt>
                        <dd>{{ $detailData['customer']['name'] }}</dd>

                        <dt>{{ __($LANG_PATH.'info.customer.email') }}</dt>
                        <dd>{{ $detailData['customer']['email'] }}</dd>

                        <dt>{{ __($LANG_PATH.'info.customer.phone') }}</dt>
                        <dd>{{ $detailData['customer']['phone'] }}</dd>
                    </dl>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __($LANG_PATH.'info.title_transaction') }}
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.code') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['uuid'] }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.total_amount') }}</dt>
                    <dd class="col-sm-8 font-weight-bold text-success">{{ $formatter::formatPrice($detailData['total_amount']) }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.total_without_discount') }}</dt>
                    <dd class="col-sm-8 font-weight-bold text-primary">{{ $formatter::formatPrice($detailData['total_without_discount']) }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.total_discount') }}</dt>
                    <dd class="col-sm-8 font-weight-bold text-danger">{{ $formatter::formatPrice($detailData['total_discount']) }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.discount_percentage') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['discount_percentage'] }}%</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.paid_date_time') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['paid_date_time'] === null ? '-' : $formatter::formatDateTime(config('values.date_format_with_hour'), $detailData['paid_date_time']) }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.status_flow') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['status_flow']['name'] }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.note') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['note'] }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.created_at') }}</dt>
                    <dd class="col-sm-8">{{ $formatter::formatDateTime(config('values.date_format_with_hour'), $detailData['created_at']) }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.created_user') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['created_user']['name'] }}</dd>

                    <br />

                    <dt class="col-sm-4">{{ __($LANG_PATH.'info.transaction.updated_user') }}</dt>
                    <dd class="col-sm-8">{{ $detailData['last_updated_user']['name'] ?? '-' }}</dd>
                </dl>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">{{ __($LANG_PATH.'info.title_transaction_items') }}</h3>
                <div class="card-toolbar float-right">
                    {{-- <a href="{{ route($ROUTE_PATH.'index') }}" class="btn btn-sm btn-danger">
                        {{ __('button.back') }}
                    </a> --}}
                </div>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <div class="table-responsive col-12 mt-3">
                    <table class="table table-bordered" style="width: 100%;" id="myTable">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>{{ __($LANG_PATH.'info.transaction.items.product') }}</th>
                                <th>{{ __($LANG_PATH.'info.transaction.items.qty') }}</th>
                                <th>{{ __($LANG_PATH.'info.transaction.items.price_per_item') }}</th>
                                <th>{{ __($LANG_PATH.'info.transaction.items.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailData['transaction_items'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['product']['name'] }}</td>
                                    <td>{{ $item['qty'] }}</td>
                                    <td>{{ $formatter::formatPrice($item['price']) }}</td>
                                    <td>{{ $formatter::formatPrice($item['total_amount']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-bold" colspan="2">
                                    Total :
                                </td>
                                <td colspan="3" class="text-left text-bold">
                                    {{ $formatter::formatPrice($detailData['total_without_discount']) }}
                                </td>
                            </tr>
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