<?php

namespace App\Repositories\Statistics;

use App\Models\StatusFlow;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class StatisticsRepository
{
    /**
     * Get user active vs inactive
     * @param bool $formatForHighchart
     * @return array
     */
    public static function getUserActiveVsInactive(bool $formatForHighchart): array
    {
        $activeUser = User::where('is_active', 1)->count();
        $inactiveUser = User::where('is_active', 0)->count();

        $results = [
            'title' => 'Active vs Inactive Users',
            'data' => [
                [
                    'name' => 'Active',
                    'data' => $activeUser
                ],
                [
                    'name' => 'Inactive',
                    'data' => $inactiveUser
                ]
            ]
        ];

        if ($formatForHighchart) {
            foreach ($results['data'] as $key => $value) {
                $results['data'][$key]['data'] = [(int) $value['data']];
            }
        }

        return $results;
    }

    /**
     * Get total number of order this year
     * @param bool $formatForHighchart
     * @return array
     */
    public static function getTotalNumberOfOrderThisYear(bool $formatForHighchart): array
    {
        $startOfYear = now()->startOfYear()->toDateString();
        $endOfYear = now()->endOfYear()->toDateString();

        $orderTransactions = Transaction::select(
            \DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            \DB::raw("COUNT(id) as data")
        )
            ->where('status_flow_id', StatusFlow::COMPLETED)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();

        $results = [];
        $results['title'] = 'Total Number of Completed Order This Year';

        foreach ($orderTransactions as $key => $value) {
            $results['data'][$key]['name'] = Carbon::createFromFormat('Y-m', $value['month'])->format('M Y');

            if ($formatForHighchart) {
                $results['data'][$key]['data'] = [(int) $value['data']];
            } else {
                $results['data'][$key]['data'] = $value['data'];
            }
        }

        return $results;
    }

    /**
     * Get total sales amount this year
     * @param bool $formatForHighchart
     * @return array
     */
    public static function getTotalSalesAmountThisYear(bool $formatForHighchart): array
    {
        $startOfYear = now()->startOfYear()->toDateString();
        $endOfYear = now()->endOfYear()->toDateString();

        $orderTransactions = Transaction::select(
            \DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            \DB::raw("SUM(total_amount) as data")
        )->where('status_flow_id', StatusFlow::COMPLETED)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->toArray();

        $results = [];
        $results['title'] = 'Total Sales Amount Completed This Year';

        foreach ($orderTransactions as $key => $value) {
            $results['data'][$key]['name'] = Carbon::createFromFormat('Y-m', $value['month'])->format('M Y');

            if ($formatForHighchart) {
                $results['data'][$key]['data'] = [(int) $value['data']];
            } else {
                $results['data'][$key]['data'] = (int)$value['data'];
            }
        }

        return $results;
    }

    /**
     * Get total sales amount grouped by status
     * @param bool $formatForHighchart
     * @return array
     */
    public static function getTotalSalesAmountGroupedByStatus(bool $formatForHighchart): array
    {
        $results = [];
        $categories = [];
        $series = [];
        $statusFlows = StatusFlow::get(['id', 'name'])->keyBy('id')->toArray();

        $results['title'] = 'Total Sales Amount Grouped By Status And Month This Year';

        $startOfYear = now()->startOfYear()->toDateString();
        $endOfYear = now()->endOfYear()->toDateString();

        $periods = CarbonPeriod::create($startOfYear, '1 month', $endOfYear);
        $categories = [];
        $series = [];

        foreach ($periods as $date) {
            $dateMonth = $date->format('Y-m');
            $categories[] = $dateMonth;
        }

        foreach ($categories as $key => $category) {
            $categories[$key] = Carbon::createFromFormat('Y-m', $category)->format('M Y');

            $orderTransactions = Transaction::select(
                DB::raw('SUM(total_amount) as total_amount'),
                'status_flow_id'
            )
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$category])
            ->groupBy('status_flow_id')
            ->get()
            ->keyBy('status_flow_id')
            ->toArray();

            foreach ($statusFlows as $statusId => $status) {
                if (!isset($series[$statusId])) {
                    $series[$statusId] = [
                        'name' => $status['name'],
                        'data' => []
                    ];
                }

                $series[$statusId]['data'][] = isset($orderTransactions[$statusId])
                    ? (int)$orderTransactions[$statusId]['total_amount']
                    : 0;
            }
        }

        $results['categories'] = $categories;
        $results['data'] = array_values($series);

        return $results;
    }
}
