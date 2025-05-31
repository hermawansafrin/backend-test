<?php

namespace App\Repositories\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class for updating role on db
 */
class Updater
{
    /** @var int */
    private int $id;

    /** @var array */
    private array $input = [];

    /** @var bool */
    private bool $usingDbTransaction = false;

    /**
     * constructor
     * @param int $id
     * @param array $input
     */
    public function prepare(int $id, array $input): self
    {
        if (isset($input['using_db_transaction'])) {
            $this->usingDbTransaction = $input['using_db_transaction'];
        }

        $this->id = $id;
        $this->input = $input;

        return $this;
    }

    /**
     * Do updating data on db with choosing schema transaction
     * @return int|null
     */
    public function execute(): ?int
    {
        $results = null;

        if ($this->usingDbTransaction) {
            DB::beginTransaction();

            try {
                $results = $this->doUpdate();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            $results = $this->doUpdate();
        }

        return $results;
    }

    /**
     * Do updating data on db
     * @return int|null
     */
    private function doUpdate(): int|null
    {
        $id = $this->id;
        $input = $this->input;

        $data = User::find($id);
        if ($data === null) {
            return null;
        }

        $data->name = $input['name'];
        $data->email = $input['email'];
        $data->is_active = (int) $input['is_active'];

        if (isset($input['password'])) {
            $data->password = Hash::make($input['password']);
        }

        $data->save();

        $currentFirstRole = $data->roles()->get()[0] ?? null;
        $currentFirstRoleId = $currentFirstRole['id'] ?? null;

        if ($currentFirstRoleId !== $input['role_id']) {
            $pastRole = Role::findOrFail($currentFirstRoleId);
            $data->removeRole($pastRole->name);

            $role = Role::findOrFail($input['role_id']);
            $data->assignRole($role);
        }

        return $data->id;
    }
}
