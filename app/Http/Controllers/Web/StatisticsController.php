<?php

namespace App\Http\Controllers\Web;

use App\Repositories\Statistics\StatisticsRepository;

class StatisticsController extends BaseController
{
    /** @var string */
    private string $ROUTE_PATH = 'dashboard.statistics.';

    /** @var string */
    private string $BLADE_PATH = 'admin.statistics.';

    /** @var string */
    private string $LANG_PATH = 'admin_statistics.';

    /** @var string */
    private ?string $PARENT_PERMISSION_NAME = null;

    /** @var string */
    private string $PERMISSION_NAME = 'statistics';

    /** @var StatisticsRepository */
    public StatisticsRepository $repo;

    /**
     * constructor
     * @param StatisticsRepository $repo
     */
    public function __construct(StatisticsRepository $repo)
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

        $userActiveVsInactive = $this->repo::getUserActiveVsInactive(true);
        $totalNumberOfOrderThisYear = $this->repo::getTotalNumberOfOrderThisYear(true);
        // dd($totalNumberOfOrderThisYear);
        $totalSalesAmountThisYear = $this->repo::getTotalSalesAmountThisYear(true);
        $totalSalesAmountGroupedByStatus = $this->repo::getTotalSalesAmountGroupedByStatus(true);

        $dataView = array_merge(
            $init,
            $this->getConstDatas(),
            $this->prepareActiveMenu([$this->PERMISSION_NAME]),
            [
                'title' => __($this->LANG_PATH . 'title'),
                'subtitle' => __($this->LANG_PATH . 'index.subtitle'),
                'userActiveVsInactive' => $userActiveVsInactive,
                'totalNumberOfOrderThisYear' => $totalNumberOfOrderThisYear,
                'totalSalesAmountThisYear' => $totalSalesAmountThisYear,
                'totalSalesAmountGroupedByStatus' => $totalSalesAmountGroupedByStatus
            ],
        );

        return view($this->BLADE_PATH . 'index', $dataView);
    }
}
