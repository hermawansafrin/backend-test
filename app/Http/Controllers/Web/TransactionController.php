<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Transaction\CannotBeChangedRequest;
use App\Http\Requests\Transaction\FindOneRequest;
use App\Http\Requests\Transaction\StoreRequest;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Models\StatusFlow;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    /** @var string */
    private string $ROUTE_PATH = 'dashboard.orders.';

    /** @var string */
    private string $BLADE_PATH = 'admin.orders.';

    /** @var string */
    private string $LANG_PATH = 'admin_orders.';

    /** @var string */
    private ?string $PARENT_PERMISSION_NAME = null;

    /** @var string */
    private string $PERMISSION_NAME = 'orders';

    /** @var TransactionRepository */
    public TransactionRepository $repo;

    /**
     * constructor
     * @param  $repo
     */
    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * get constant datas for this controller
     * @return array
     */
    private function getConstDatas(): array
    {
        return [
            'ROUTE_PATH' => $this->ROUTE_PATH,
            'BLADE_PATH' => $this->BLADE_PATH,
            'LANG_PATH' => $this->LANG_PATH,
            'PARENT_PERMISSION_NAME' => $this->PARENT_PERMISSION_NAME,
            'PERMISSION_NAME' => $this->PERMISSION_NAME
        ];
    }

    /**
     * get references
     * @param array $input
     * @return array
     */
    private function getReferences(array $input): array
    {
        $results = [];

        if (!empty($input['has_status_flows'])) {
            $statusFlows = StatusFlow::select(['id', 'name'])->get()->toArray();
            $results['status_flows'] = $statusFlows;
        }

        if (!empty($input['has_customers'])) {
            $customers = app(CustomerRepository::class)->get(['is_paginate' => false, 'is_using_yajra' => 0]);
            $results['customers'] = $customers;
        }

        if (!empty($input['has_products'])) {
            $input['key_by_id'] = true;
            $products = app(ProductRepository::class)->get(['is_paginate' => false, 'is_using_yajra' => 0, 'only_active' => 1, 'has_stock' => 1, 'key_by_id' => true]);
            $results['products'] = $products;
        }

        return $results;
    }

    /**
     * show index page
     */
    public function index()
    {
        $init = $this->init();
        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'index.subtitle'),
            ],
            $this->getReferences([
                'has_status_flows' => 1,
            ])
        );

        return view($this->BLADE_PATH . 'index', $dataView);
    }

    /**
     * show detail page
     * @param FindOneRequest $request
     * @param int $id
     */
    public function detail(FindOneRequest $request, int $id)
    {
        $init = $this->init();
        $detailData = $this->repo->findOne($id);

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'detail.subtitle'),
                'detailData' => $detailData,
            ],
        );

        // dd($dataView);

        return view($this->BLADE_PATH . 'detail', $dataView);
    }

    /**
     * show create page
     */
    public function create()
    {
        $init = $this->init();
        $roleRepo = app(RoleRepository::class);
        $roles = $roleRepo->get(['is_paginate' => false, 'is_using_yajra' => 0]);

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            $this->getReferences([
                'has_status_flows' => 0,
                'has_customers' => 1,
                'has_products' => 1,
            ]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'create.subtitle'),
                'roles' => $roles,
            ]
        );

        // dd($dataView);

        return view($this->BLADE_PATH . 'create', $dataView);
    }

    /**
     * Do storeing transaction on db
     * @param StoreRequest $request
     */
    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $input['using_db_transaction'] = true;

        $data = $this->repo->create($input);

        return redirect()
            ->route($this->ROUTE_PATH . 'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', [
                    'action' => __('messages.action_add'),
                ])
            );
    }

    /**
     * show edit page
     * @param FindOneRequest $request
     * @param int $id
     */
    public function edit(FindOneRequest $request, int $id)
    {
        $init = $this->init();

        $editedData = $this->repo->findOne($id);
        $roleRepo = app(RoleRepository::class);
        $roles = $roleRepo->get(['is_paginate' => false, 'is_using_yajra' => 0]);

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            ['title' => __($this->LANG_PATH.'title'), 'subtitle' => __($this->LANG_PATH.'edit.subtitle')],
            ['params' => ['id' => $id]],
            ['editedData' => $editedData, 'roles' => $roles],
            $this->getReferences([
                'has_status_flows' => 0,
                'has_customers' => 1,
                'has_products' => 1,
            ]),
        );

        // dd($dataView);

        return view($this->BLADE_PATH.'edit', $dataView);
    }

    /**
     * Do updating transaction on db
     * @param UpdateRequest $request
     * @param int $id
     */
    public function update(UpdateRequest $request, int $id)
    {
        $input = $request->validated();
        $input['using_db_transaction'] = true;
        $data = $this->repo->update($id, $input);

        return redirect()
            ->route($this->ROUTE_PATH.'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', ['action' => __('messages.action_edit')])
            );
    }

    /**
     * Do completing transaction on db
     * @param CannotBeChangedRequest $request
     * @param int $id
     */
    public function complete(CannotBeChangedRequest $request, int $id)
    {
        $this->repo->complete($id, ['using_db_transaction' => true, 'paid_date_time' => $request->paid_date_time]);

        return redirect()
            ->route($this->ROUTE_PATH.'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', ['action' => __('messages.action_complete')])
            );
    }

    /**
     * Do cancelling transaction on db
     * @param CannotBeChangedRequest $request
     * @param int $id
     */
    public function cancel(CannotBeChangedRequest $request, int $id)
    {
        $this->repo->cancel($id, ['using_db_transaction' => true]);

        return redirect()
            ->route($this->ROUTE_PATH.'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', ['action' => __('messages.action_cancel')])
            );
    }

    /**
     * Do deleting transaction on db
     * @param CannotBeChangedRequest $request
     * @param int $id
     */
    public function destroy(CannotBeChangedRequest $request, int $id)
    {
        $this->repo->delete($id, ['using_db_transaction' => true]);

        return redirect()
            ->route($this->ROUTE_PATH.'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', ['action' => __('messages.action_delete')])
            );
    }

    /**
     * get data with yajra format
     * @param Request $request
     */
    public function getYajra(Request $request)
    {
        $input = $request->all();
        $input['is_using_yajra'] = 1;
        return $this->repo->get($input);
    }
}
