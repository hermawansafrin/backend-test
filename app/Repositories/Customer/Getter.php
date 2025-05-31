<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;

class Getter
{
    /** @var string */
    private string $ROUTE_NAME = 'dashboard.customers.';

    /** @var string */
    private string $LANG_PATH = 'admin_customers.';

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
     * Melakukan eksekusi proses pengambilan data logs
     * @return mixed
     */
    public function execute(): mixed
    {
        $input = $this->input;

        $query = Customer::select([
                'customers.id as id',
                'customers.name as name',
                'customers.email as email',
                'customers.phone as phone',
            ]);

        if ($input['search_values'] !== null) {
            $searchValues = "%".$input['search_values']."%";
            $query->where(function ($q) use ($searchValues) {
                $q->whereRaw("(customers.name LIKE ?)", [$searchValues])
                    ->orWhereRaw("(customers.email LIKE ?)", [$searchValues])
                    ->orWhereRaw("(customers.phone LIKE ?)", [$searchValues]);
            });
        }

        $query->orderBy("customers.name", "ASC");

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
            ->addColumn('name', function ($query) {
                return $query->name ?? '-';
            })
            ->addColumn('email', function ($query) {
                return $query->email ?? '-';
            })
            ->addColumn('phone', function ($query) {
                return $query->phone ?? '-';
            })
            ->addColumn('action', function ($query) {
                $editLabel = __("button.edit");
                $editUrl = route($this->ROUTE_NAME.'edit', ['id' => $query->id]);

                $deleteLabel = __("button.delete");
                $deleteUrl = route($this->ROUTE_NAME.'delete', ['id' => $query->id]);
                $deleteConfirmation = __("messages.confirmation.delete");

                return '
                    <a class="btn btn-sm btn-warning m-1" href="'.$editUrl.'">'.$editLabel.'</a>
                    <form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                        '.method_field('DELETE').'
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm(\''.$deleteConfirmation.'\')">'.$deleteLabel.'</button>
                    </form>
                ';
            })
            ->rawColumns([])
            ->make(true)
        ;
    }

    /**
     * Get one data by id
     * @param int $id
     * @param array|null
     */
    public function simpleFindOne(int $id): array|null
    {
        $data = Customer::select([
            'customers.id as id',
            'customers.name as name',
            'customers.email as email',
            'customers.phone as phone',
        ])->find($id);

        if ($data === null) {
            return null;
        }

        return $data->toArray();
    }

    /**
     * Find one user by id
     * @param int $id
     * @return array|null
     */
    public function findOne(int $id): array|null
    {
        return $this->simpleFindOne($id);
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
        ];

        return $input;
    }
}
