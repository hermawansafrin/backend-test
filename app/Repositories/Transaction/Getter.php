<?php

namespace App\Repositories\Transaction;

use App\Helpers\FormatterHelper;
use App\Models\StatusFlow;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class Getter
{
    /** @var string */
    private string $ROUTE_NAME = 'dashboard.orders.';

    /** @var string */
    private string $LANG_PATH = 'admin_orders.';

    /** @var array */
    private array $input = [];

    /**
     * function for prepare input
     * @param array $request
     * @return self
     */
    public function prepare(array $request): self
    {
        $this->input = $this->formatInput($request);
        return $this;
    }

    /**
     * Get transaction query
     * @return Builder
     */
    private function transactionQuery(): Builder
    {
        $query = Transaction::select([
                'transactions.id as id',
                'transactions.uuid as uuid',
                'transactions.customer_id as customer_id',
                'transactions.status_flow_id as status_flow_id',
                'transactions.total_amount as total_amount',
                'transactions.total_without_discount as total_without_discount',
                'transactions.total_discount as total_discount',
                'transactions.discount_percentage as discount_percentage',
                'transactions.paid_date_time as paid_date_time',
                'transactions.note as note',
                'transactions.created_user_id as created_user_id',
                'transactions.last_updated_user_id as last_updated_user_id',
                'transactions.created_at as created_at',
            ])
            ->with([
                'customer:id,name,email,phone',
                'status_flow:id,name',
                'created_user:id,name',
                'last_updated_user:id,name',
                'transaction_items' => function ($query) {
                    $query->select([
                        'id',
                        'transaction_id',
                        'product_id',
                        'qty',
                        'price',
                        'total_amount',
                    ]);
                    $query->with([
                        'product:id,name,price',
                    ]);
                },
        ]);

        return $query;
    }

    /**
     * Melakukan eksekusi proses pengambilan data logs
     * @return mixed
     */
    public function execute(): mixed
    {
        $input = $this->input;

        $query = $this->transactionQuery();

        /** filter data with search_values and join query builder to customers instead using whereHas from orm cause performance issue */
        if ($input['search_values'] !== null) {
            $searchValues = "%".$input['search_values']."%";

            /** join implemented for filter only cause i dont want using whereHas (cause performance issue) */
            $query->join('customers', 'transactions.customer_id', '=', 'customers.id');

            $query->where(function ($q) use ($searchValues) {
                $q->whereRaw("(transactions.uuid LIKE ?)", [$searchValues])
                    ->orWhereRaw("(customers.name LIKE ?)", [$searchValues])
                    ->orWhereRaw("(customers.email LIKE ?)", [$searchValues])
                    ->orWhereRaw("(customers.phone LIKE ?)", [$searchValues]);
            });
        }

        /** range total_amount */
        if ($input['min_total_amount'] !== null) {
            $query->whereRaw("(transactions.total_amount >= ?)", [$input['min_total_amount']]);
        }
        if ($input['max_total_amount'] !== null) {
            $query->whereRaw("(transactions.total_amount <= ?)", [$input['max_total_amount']]);
        }

        /** range created_at */
        if ($input['min_created_at'] !== null) {
            $query->whereRaw("(transactions.created_at >= ?)", [$input['min_created_at']." 00:00:00"]);
        }
        if ($input['max_created_at'] !== null) {
            $query->whereRaw("(transactions.created_at <= ?)", [$input['max_created_at']." 23:59:59"]);
        }

        /** filter status flow */
        if ($input['status_flow_id'] !== null) {
            $query->whereRaw("(transactions.status_flow_id = ?)", [(int) $input['status_flow_id']]);
        }

        /**
         * Order by status flow first (asc)
         * and order by created at (asc)
         */
        $query->orderBy('transactions.status_flow_id', 'ASC');
        $query->orderBy('transactions.created_at', 'ASC');

        if ($input['is_using_yajra'] == 1) {
            return $this->getYajra($query);
        }

        if ($input['is_paginate']) {
            return $query->paginate($input['per_page'])->toArray();
        } else {
            return $query->get()->toArray();
        }
    }

    /**
     * Format get data untuk kebutuhan yajra
     * @param $query
     */
    public function getYajra($query)
    {
        return DataTables::of($query)
            ->addIndexColumn()//nomor urut
            ->addColumn('code', function ($query) {
                return $query->uuid;
            })
            ->addColumn('customer', function ($query) {
                $nameLabel = '<span class="text-primary">'.__($this->LANG_PATH.'info.customer.name').':</span>';
                $emailLabel = '<span class="text-primary">'.__($this->LANG_PATH.'info.customer.email').':</span>';
                $phoneLabel = '<span class="text-primary">'.__($this->LANG_PATH.'info.customer.phone').':</span>';

                return $nameLabel.'<br />'.$query->customer->name.'<br />'.$emailLabel.'<br />'.$query->customer->email.'<br />'.$phoneLabel.'<br />'.$query->customer->phone;
            })
            ->addColumn('total_amount', function ($query) {
                return FormatterHelper::formatPrice($query->total_amount);
            })
            ->addColumn('status_flow', function ($query) {
                return $query->status_flow->name;
            })
            ->addColumn('paid_date_time', function ($query) {
                return $query->paid_date_time === null ? '-' : FormatterHelper::formatDateTime(config('values.date_format_with_hour'), $query->paid_date_time);
            })
            ->addColumn('created_at', function ($query) {
                return FormatterHelper::formatDateTime(config('values.date_format_with_hour'), $query->created_at);
            })
            ->addColumn('action', function ($query) {
                $htmBody = "";

                $detailLabel = __("button.detail");
                $detailUrl = route($this->ROUTE_NAME.'detail', ['id' => $query->id]);
                $htmBody .= '<a class="btn btn-sm btn-primary m-1" href="'.$detailUrl.'">'.$detailLabel.'</a>';

                /** cannot be changed when status is not new */
                if ($query->status_flow_id != StatusFlow::NEW) {
                    return $htmBody;
                }

                if ($query->status_flow_id !== StatusFlow::CANCELLED) {
                    $cancelLabel = __("button.cancel");
                    $cancelUrl = route($this->ROUTE_NAME.'cancel', ['id' => $query->id]);
                    $cancelConfirmation = __("messages.confirmation.cancel");

                    $htmBody .= '<form action="'.$cancelUrl.'" method="POST" style="display:inline;">
                        '.method_field('PATCH').'
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-info m-1" onclick="return confirm(\''.$cancelConfirmation.'\')">'.$cancelLabel.'</button>
                    </form>';
                }

                if ($query->status_flow_id !== StatusFlow::COMPLETED) {
                    $completeLabel = __("button.complete");
                    $completeUrl = route($this->ROUTE_NAME.'complete', ['id' => $query->id]);
                    $completeConfirmation = __("messages.confirmation.complete");

                    $htmBody .= '<button type="button" class="btn btn-sm btn-success m-1 complete-btn"
                        data-id="'.$query->id.'"
                        data-url="'.$completeUrl.'"
                        data-code="'.$query->uuid.'"
                        data-customer-name="'.$query->customer->name.'"
                        data-bs-toggle="modal"
                        data-bs-target="#completeModal">'.$completeLabel.'</button>';
                }

                if (!in_array($query->status_flow_id, StatusFlow::STATUS_CANNOT_CHANGE_IDS)) {
                    $editLabel = __("button.edit");
                    $editUrl = route($this->ROUTE_NAME.'edit', ['id' => $query->id]);
                    $htmBody .= '<a class="btn btn-sm btn-warning m-1" href="'.$editUrl.'">'.$editLabel.'</a>';
                }

                $deleteLabel = __("button.delete");
                $deleteUrl = route($this->ROUTE_NAME.'delete', ['id' => $query->id]);
                $deleteConfirmation = __("messages.confirmation.delete");
                $htmBody .= '<form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                    '.method_field('DELETE').'
                    '.csrf_field().'
                    <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm(\''.$deleteConfirmation.'\')">'.$deleteLabel.'</button>
                </form>';

                return $htmBody;
            })
            ->rawColumns(['customer', 'action'])
            ->make(true)
        ;
    }

    /**
     * Find one transaction by id
     * @param int $id
     * @return array|null
     */
    public function findOne(int $id): array|null
    {
        $query = $this->transactionQuery();
        $data = $query->find($id);

        if ($data === null) {
            return null;
        }

        return $data->toArray();
    }

    /**
     * Get one data by id
     * @param int $id
     * @param array|null
     */
    public function simpleFindOne(int $id): array|null
    {
        /** currently im setting same data with findOne */
        return $this->findOne($id);
    }

    /**
     * Melakukan proses formatting input
     * @param array $request
     * @return array
     */
    private function formatInput(array $request): array
    {
        $input = [
            'is_paginate' => $request['is_paginate'] ?? true,
            'per_page' => $request['per_page'] ?? config('values.default_per_page'),
            'is_using_yajra' => $request['is_using_yajra'] ?? 1,
            'page' => $request['page'] ?? 1,
            'search_values' => $request['search_values'] ?? null,
            'min_total_amount' => $request['min_total_amount'] ?? null,
            'max_total_amount' => $request['max_total_amount'] ?? null,
            'min_created_at' => $request['min_created_at'] ?? null,
            'max_created_at' => $request['max_created_at'] ?? null,
            'status_flow_id' => $request['status_flow_id'] ?? null,
        ];

        return $input;
    }
}
