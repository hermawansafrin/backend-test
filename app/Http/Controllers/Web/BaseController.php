<?php

namespace App\Http\Controllers\Web;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * get init menus data for access controller page
     * @return array
     */
    public function init(): array
    {
        $menuHelper = app(MenuHelper::class);
        // $userHelper = app(UserHelper::class);

        $results = [
            'menus' => $menuHelper->getMenus(true),
            // 'user' => $userHelper->getUser(),
        ];

        return $results;
    }

    /**
     * prepare active menu
     * @param array --> array plain (index 0, 1, 2)
     * @return array
     */
    public function prepareActiveMenu(array $datas): array
    {
        $maxDepth = 2;// for now max dept only 2
        /** @var array $results */
        $results = [];

        for ($i = 0; $i < 2; $i++) {
            $prefixKey = "isActive";
            $currentLevel = $i + 1;
            $currentKey = "{$prefixKey}{$currentLevel}";

            $results[$currentKey] = null;//default
            /** if there is index related, then fill as value isActive */
            if (isset($datas[$i])) {
                $results[$currentKey] = $datas[$i];
            }
        }

        return $results;
    }
}
