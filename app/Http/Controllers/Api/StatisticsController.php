<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Statistics\StatisticsRepository;
use Illuminate\Http\Request;

class StatisticsController extends BaseController
{
    /**
     * @var StatisticsRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param StatisticsRepository $repo
     */
    public function __construct(StatisticsRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/statistics/userActiveVsInactive",
     *      summary="Get statistics for user active vs inactive",
     *      tags={"Statistics"},
     *      description="Get statistics for user active vs inactive",
     *      security={{"Bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function userActiveVsInactive(Request $request)
    {
        $data = $this->repo::getUserActiveVsInactive(false);

        return $this->sendResponse($data, __('messages.retrieved'));
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/statistics/totalNumberOfOrderThisYear",
     *      summary="Get statistics for total number of completed order this year",
     *      tags={"Statistics"},
     *      description="Get statistics for total number of completed order this year",
     *      security={{"Bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function totalNumberOfOrderThisYear(Request $request)
    {
        $data = $this->repo::getTotalNumberOfOrderThisYear(false);

        return $this->sendResponse($data, __('messages.retrieved'));
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/statistics/totalSalesAmountThisYear",
     *      summary="Get statistics for total sales amount completed this year",
     *      tags={"Statistics"},
     *      description="Get statistics for total sales amount completed this year",
     *      security={{"Bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function totalSalesAmountThisYear(Request $request)
    {
        $data = $this->repo::getTotalSalesAmountThisYear(false);

        return $this->sendResponse($data, __('messages.retrieved'));
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/statistics/totalSalesAmountGroupedByStatus",
     *      summary="Get statistics for total sales amount grouped by status and month",
     *      tags={"Statistics"},
     *      description="Get statistics for total sales amount grouped by status and month",
     *      security={{"Bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function totalSalesAmountGroupedByStatus(Request $request)
    {
        $data = $this->repo::getTotalSalesAmountGroupedByStatus(false);

        return $this->sendResponse($data, __('messages.retrieved'));
    }
}
