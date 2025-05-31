<?php

namespace App\Http\Controllers\Web;

class DashboardController extends BaseController
{
    /** @var string */
    private string $ROUTE_PATH = 'admin.dashboard.';

    /** @var string */
    private string $BLADE_PATH = 'admin.';

    /** @var string */
    private string $LANG_PATH = 'admin_dashboard.';

    /** @var string|null */
    private ?string $PARENT_PERMISSION_NAME = null;

    /** @var string */
    private string $PERMISSION_NAME = 'home';

    /**
     * get const datas
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
                'title' => __($this->LANG_PATH . 'index.title'),
            ]
        );

        return view($this->BLADE_PATH . 'index', $dataView);
    }
}
