<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Customer\ApiDeleteRequest;
use App\Http\Requests\Customer\ApiFindOneRequest;
use App\Http\Requests\Customer\ApiStoreRequest;
use App\Http\Requests\Customer\ApiUpdateRequest;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    /**
     * @var CustomerRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param CustomerRepository $repo
     */
    public function __construct(CustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/customers",
     *      summary="Get all customers",
     *      tags={"Customers"},
     *      description="Get all customers",
     *      security={{"Bearer":{}}},
     *       @OA\Parameter(
     *          name="search_values",
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
     *      path="/customers/{id}",
     *      summary="get customer by id",
     *      tags={"Customers"},
     *      description="get customer by id",
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
     *     path="/customers",
     *      summary="create customers",
     *      tags={"Customers"},
     *      description="create customers",
     *      security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateCustomer")
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
     *     path="/customers/{id}",
     *      summary="update customer",
     *      tags={"Customers"},
     *      description="update customer by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCustomer")
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
     * @OA\Delete(
     *     path="/customers/{id}",
     *      summary="delete customers",
     *      tags={"Customers"},
     *      description="delete customer by id",
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
