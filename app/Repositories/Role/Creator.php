<?php

namespace App\Repositories\Role;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Creator
{
    /** @var array */
    private array $input = [];

    /** @var bool */
    private bool $usingDbTransaction = true;

    /**
     * Prepare input data
     * @param array $input
     * @return self
     */
    public function prepare(array $input): self
    {
        if (isset($input['using_db_transaction'])) {
            $this->usingDbTransaction =  $input['using_db_transaction'];
            unset($input['using_db_transaction']);
        }

        $this->input = $input;

        return $this;
    }

    /**
     * Execute process for create data
     * @return int|null
     */
    public function execute(): ?int
    {
        $result = null;

        if ($this->usingDbTransaction) {
            DB::beginTransaction();
            try {
                $result = $this->doStore();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            $result = $this->doStore();
        }

        return $result;
    }

    /**
     * Do storeing data on db
     * @return int|null
     */
    private function doStore(): int|null
    {
        $input = $this->input;
        $permissionIds = $input['permission_ids'];

        //give permission_id valid
        $permissionIds = Helper::adjustPermissionIds($permissionIds);

        $role = Role::create(['name' => $input['name']]);

        $dataId = $role->id ?? null;

        if ($dataId === null) {
            return $dataId;
        }

        foreach ($permissionIds as $permissionId) {
            //give permission one by one
            $permission = Permission::findOrFail($permissionId);

            //add role
            $role->givePermissionTo($permission);
        }

        return $dataId;
    }
}
