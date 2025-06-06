<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Role\ApiDeleteRequest;
use App\Http\Requests\Role\ApiFindOneRequest;
use App\Http\Requests\Role\ApiStoreRequest;
use App\Http\Requests\Role\ApiUpdateRequest;
use App\Repositories\Role\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /**
     * @var RoleRepository
     */
    protected $repo;

    /**
     * Constructor class
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Response
     *
     * @OA\Get(
     *      path="/roles",
     *      summary="Get all roles",
     *      tags={"Roles"},
     *      description="Get all roles",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
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
     *      path="/roles/{id}",
     *      summary="get role by id",
     *      tags={"Roles"},
     *      description="get role by id",
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
     *     path="/roles",
     *      summary="create role",
     *      tags={"Roles"},
     *      description="create role",
     *      security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateRole")
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
     *     path="/roles/{id}",
     *      summary="update role",
     *      tags={"Roles"},
     *      description="update role by id",
     *      security={{"Bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateRole")
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
     *     path="/roles/{id}",
     *      summary="delete role",
     *      tags={"Roles"},
     *      description="delete role by id",
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
