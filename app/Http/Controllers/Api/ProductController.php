<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Product\ApiDeleteRequest;
use App\Http\Requests\Product\ApiFindOneRequest;
use App\Http\Requests\Product\ApiStoreRequest;
use App\Http\Requests\Product\ApiUpdateRequest;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * @var ProductRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param ProductRepository $repo
     */
    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/products",
     *      summary="Get all products",
     *      tags={"Products"},
     *      description="Get all products",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="search_values",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="min_stock",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="max_stock",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="min_price",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="max_price",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer")
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
     *      path="/products/{id}",
     *      summary="get products by id",
     *      tags={"Products"},
     *      description="get products by id",
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
     *     path="/products",
     *      summary="create products",
     *      tags={"Products"},
     *      description="create products",
     *      security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateProduct")
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
     *     path="/products/{id}",
     *      summary="update products",
     *      tags={"Products"},
     *      description="update products by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateProduct")
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
     *     path="/products/{id}",
     *      summary="delete products",
     *      tags={"Products"},
     *      description="delete products by id",
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
