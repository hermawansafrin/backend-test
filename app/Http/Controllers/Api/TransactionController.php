<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Transaction\ApiCannotBeChangedRequest;
use App\Http\Requests\Transaction\ApiDeleteRequest;
use App\Http\Requests\Transaction\ApiFindOneRequest;
use App\Http\Requests\Transaction\ApiStoreRequest;
use App\Http\Requests\Transaction\ApiUpdateRequest;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    /**
     * @var TransactionRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param TransactionRepository $repo
     */
    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/orders",
     *      summary="Get all orders",
     *      tags={"Orders"},
     *      description="Get all orders",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="search_values",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="status_flow_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="min_total_amount",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="max_total_amount",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="min_created_at",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="max_created_at",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function paginate(Request $request)
    {
        $input = $request->all();
        $input['is_paginate'] = true;
        $input['is_using_yajra'] = 0;
        $data = $this->repo->get($input);

        return $this->sendResponse($data, __('messages.retrieved'));
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/orders/{id}",
     *      summary="get orders by id",
     *      tags={"Orders"},
     *      description="get orders by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function show(ApiFindOneRequest $request, $id)
    {
        $data = $this->repo->findOne($id, true, true);

        return $this->sendResponse($data, __('messages.retrieved'));
    }

    /**
     * @return Response
     *
     * @OA\Post(
     *     path="/orders",
     *      summary="create orders",
     *      tags={"Orders"},
     *      description="create orders",
     *      security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateTransaction")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function store(ApiStoreRequest $request)
    {
        $data = $this->repo->create($request->validated());

        return $this->sendResponse($data, __('messages.created'));
    }

    /**
     * @return Response
     *
     * @OA\Put(
     *     path="/orders/{id}",
     *      summary="update orders",
     *      tags={"Orders"},
     *      description="update orders by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTransaction")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function update(ApiUpdateRequest $request, $id)
    {
        $data = $this->repo->update($id, $request->validated());

        return $this->sendResponse($data, __('messages.updated'));
    }

    /**
     * @return Response
     *
     * @OA\Patch(
     *     path="/orders/{id}/complete",
     *      summary="complete orders",
     *      tags={"Orders"},
     *      description="complete orders",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CompleteTransaction")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function complete(ApiCannotBeChangedRequest $request, $id)
    {
        $data = $this->repo->complete($id, ['paid_date_time' => $request->paid_date_time]);

        return $this->sendResponse($data, __('messages.updated'));
    }

    /**
     * @return Response
     *
     * @OA\Patch(
     *     path="/orders/{id}/cancel",
     *      summary="cancel orders",
     *      tags={"Orders"},
     *      description="cancel orders",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function cancel(ApiCannotBeChangedRequest $request, $id)
    {
        $data = $this->repo->cancel($id, ['using_db_transaction' => true]);

        return $this->sendResponse($data, __('messages.updated'));
    }

    /**
     * @return Response
     *
     * @OA\Delete(
     *     path="/orders/{id}",
     *      summary="delete orders",
     *      tags={"Orders"},
     *      description="delete orders by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      )
     * )
     */
    public function destroy(ApiDeleteRequest $request, $id)
    {
        $data = $this->repo->delete($id);

        return $this->sendResponse($data, __('messages.deleted'));
    }
}
