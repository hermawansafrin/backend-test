<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\ApiDeleteRequest;
use App\Http\Requests\User\ApiFindOneRequest;
use App\Http\Requests\User\ApiStoreRequest;
use App\Http\Requests\User\ApiUpdateRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/users",
     *      summary="Get all users",
     *      tags={"Users"},
     *      description="Get all users",
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
     *      path="/users/{id}",
     *      summary="get user by id",
     *      tags={"Users"},
     *      description="get user by id",
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
     *     path="/users",
     *      summary="create user",
     *      tags={"Users"},
     *      description="create user",
     *      security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateUser")
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
     *     path="/users/{id}",
     *      summary="update user",
     *      tags={"Users"},
     *      description="update user by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUser")
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
     *     path="/users/{id}",
     *      summary="delete user",
     *      tags={"Users"},
     *      description="delete user by id",
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
