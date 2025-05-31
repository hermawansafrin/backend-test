<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Product\DeleteRequest;
use App\Http\Requests\Product\FindOneRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /** @var string */
    private string $ROUTE_PATH = 'dashboard.products.';

    /** @var string */
    private string $BLADE_PATH = 'admin.products.';

    /** @var string */
    private string $LANG_PATH = 'admin_products.';

    /** @var string */
    private ?string $PARENT_PERMISSION_NAME = null;

    /** @var string */
    private string $PERMISSION_NAME = 'products';

    /** @var ProductRepository */
    public ProductRepository $repo;

    /**
     * constructor
     * @param ProductRepository $repo
     */
    public function __construct(ProductRepository $repo)
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
            ]
        );

        return view($this->BLADE_PATH . 'index', $dataView);
    }

    /**
     * show create page
     */
    public function create()
    {
        $init = $this->init();

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'create.subtitle'),
            ]
        );

        return view($this->BLADE_PATH . 'create', $dataView);
    }

    /**
     * Do storeing role on db
     * @param StoreRequest $request
     */
    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $input['using_db_transaction'] = true;

        $role = $this->repo->create($input);

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

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([
                $this->PERMISSION_NAME
            ]),
            ['title' => __($this->LANG_PATH.'title'), 'subtitle' => __($this->LANG_PATH.'edit.subtitle')],
            ['params' => ['id' => $id]],
            ['editedData' => $editedData],
        );

        return view($this->BLADE_PATH.'edit', $dataView);
    }

    /**
     * Do updating role on db
     * @param UpdateRequest $request
     * @param int $id
     */
    public function update(UpdateRequest $request, int $id)
    {
        $input = $request->validated();
        $input['using_db_transaction'] = true;
        $role = $this->repo->update($id, $input);

        return redirect()
            ->route($this->ROUTE_PATH.'index')
            ->with(
                'status_success',
                __('messages.session.success.subtitle', ['action' => __('messages.action_edit')])
            );
    }

    /**
     * Do deleting role on db
     * @param DeleteRequest $request
     * @param int $id
     */
    public function destroy(DeleteRequest $request, int $id)
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
