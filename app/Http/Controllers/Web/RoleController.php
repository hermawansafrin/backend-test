<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Role\DeleteRequest;
use App\Http\Requests\Role\FindOneRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Role\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /** @var string */
    private string $ROUTE_PATH = 'dashboard.settings.roles.';

    /** @var string */
    private string $BLADE_PATH = 'admin.settings.roles.';

    /** @var string */
    private string $LANG_PATH = 'admin_setting_roles.';

    /** @var string */
    private string $PARENT_PERMISSION_NAME = 'settings';

    /** @var string */
    private string $PERMISSION_NAME = 'settings_role';

    /** @var RoleRepository */
    public RoleRepository $repo;

    /**
     * constructor
     * @param RoleRepository $repo
     */
    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Mendapatkan data constanta
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
            $this->prepareActiveMenu([$this->PARENT_PERMISSION_NAME, $this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'index.subtitle'),
            ]
        );

        // dd($dataView);

        return view($this->BLADE_PATH . 'index', $dataView);
    }

    /**
     * show create page
     */
    public function create()
    {
        $init = $this->init();
        $permissionRepo = app(PermissionRepository::class);
        $permissions = $permissionRepo->get();

        // dd($permissions);

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PARENT_PERMISSION_NAME, $this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'create.subtitle'),
                'permissions' => $permissions,
            ]
        );

        // dd($dataView);

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

        $permissionRepo = app(PermissionRepository::class);
        $role = $this->repo->findOne($id, true, false);//with permisison and not flatten

        $editedData = [
            'role' => $role,
            'permission_role_name' => collect($role['permissions'])->pluck('name')->values()->toArray() ?? [],
            'permissions' => $permissionRepo->get(),
        ];

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([
                $this->PARENT_PERMISSION_NAME,
                $this->PERMISSION_NAME
            ]),
            ['title' => __($this->LANG_PATH.'title'), 'subtitle' => __($this->LANG_PATH.'edit.subtitle')],
            ['params' => ['id' => $id]],
            ['editedData' => $editedData],
        );

        // dd($dataView);

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
