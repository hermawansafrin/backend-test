<?php

namespace App\Repositories\Product;

use App\Helpers\FormatterHelper;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class Getter
{
    /** @var string */
    private string $ROUTE_NAME = 'dashboard.products.';

    /** @var string */
    private string $LANG_PATH = 'admin_products.';

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

        $query = Product::select([
                'products.id as id',
                'products.name as name',
                'products.price as price',
                'products.stock as stock',
                'products.is_active as is_active',
            ]);

        if ($input['search_values'] !== null) {
            $searchValues = "%".$input['search_values']."%";
            $query->whereRaw("(products.name LIKE ?)", [$searchValues]);
        }

        if ($input['only_active'] == 1) {
            $query->whereRaw("(products.is_active = ?)", [1]);
        }

        if ($input['has_stock'] == 1) {
            $query->where('products.stock', '>', 0);
        }

        if ($input['min_stock'] !== null) {
            $query->where('products.stock', '>=', (int) $input['min_stock']);
        }

        if ($input['max_stock'] !== null) {
            $query->where('products.stock', '<=', (int) $input['max_stock']);
        }

        if ($input['min_price'] !== null) {
            $query->where('products.price', '>=', (int) $input['min_price']);
        }

        if ($input['max_price'] !== null) {
            $query->where('products.price', '<=', (int) $input['max_price']);
        }

        $query->orderBy("products.name", "ASC");

        if ($input['is_using_yajra'] == 1) {
            return $this->getYajra($query);
        }

        if ($input['is_paginate']) {
            return $query->paginate($input['per_page'])->toArray();
        } else {
            if ($input['key_by_id']) {
                return $query->get()->keyBy('id')->toArray();
            } else {
                return $query->get()->toArray();
            }
        }
    }

    /**
     * Get products by ids
     * @param array $ids
     * @param bool $keyById
     * @return array|null
     */
    public function getByIds(array $ids, bool $keyById): array|null
    {
        $query = Product::select([
            'products.id as id',
            'products.name as name',
            'products.price as price',
            'products.stock as stock',
        ])
        ->whereIntegerInRaw('id', $ids)
        ->get();

        if ($query->isEmpty()) {
            return null;
        }

        if ($keyById) {
            return $query->keyBy('id')->toArray();
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
            ->addColumn('price', function ($query) {
                return FormatterHelper::formatPrice($query->price);
            })
            ->addColumn('is_active', function ($query) {
                return $query->is_active == 1 ? __('general.active') : __('general.inactive');
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
        $data = Product::select([
            'products.id as id',
            'products.name as name',
            'products.price as price',
            'products.stock as stock',
            'products.is_active as is_active',
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
            'min_stock' => $request['min_stock'] ?? null,
            'max_stock' => $request['max_stock'] ?? null,
            'min_price' => $request['min_price'] ?? null,
            'max_price' => $request['max_price'] ?? null,
            'only_active' => $request['only_active'] ?? 0,
            'has_stock' => $request['has_stock'] ?? 0,
            'key_by_id' => $request['key_by_id'] ?? false,
        ];

        return $input;
    }
}
