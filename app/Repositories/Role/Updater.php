<?php

namespace App\Repositories\Role;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

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
        $input = $this->input;
        $permissionIds = $input['permission_ids'];
        $permissionIds = Helper::adjustPermissionIds($permissionIds);

        $role = Role::findOrFail($this->id);
        $role->name = $input['name'];
        $role->guard_name = 'web';

        $role->save();

        /** clear permission first */
        $role->syncPermissions([]);

        foreach ($permissionIds as $permissionId) {
            $permission = Permission::findOrFail($permissionId);
            $role->givePermissionTo($permission);
        }

        return $role->id;
    }
}
