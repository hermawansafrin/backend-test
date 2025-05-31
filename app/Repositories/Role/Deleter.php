<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * Special class for delete process
 */
class Deleter
{
    /** @var int */
    private int $id;

    /** @var array */
    private array $option = [];

    /** @var bool */
    private bool $usingDbTransaction = true;

    /**
     * Perform preparation process before delete
     * @param int $id
     * @param array $options
     * @return self
     */
    public function prepare(int $id, array $options): self
    {
        if (isset($options['using_db_transaction'])) {
            $this->usingDbTransaction = $options['using_db_transaction'] ?? true;
            unset($options['using_db_transaction']);
        }

        $this->id = $id;
        $this->option = $options;

        return $this;
    }

    /**
     * Perform delete process with transaction check
     * @return void
     */
    public function execute(): void
    {
        $usingDbTransaction = $this->usingDbTransaction;
        if ($usingDbTransaction) {//use this if there was a previous transaction and commit process (starting new transaction)
            DB::beginTransaction();
            try {
                $this->doDelete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {//use this if there was a previous transaction and no commit process yet (part of transaction chain)
            $this->doDelete();
        }
    }

    /**
     * Perform delete process
     * @return void
     */
    public function doDelete(): void
    {
        $id = $this->id;

        $role = Role::findOrFail($id);

        if ($role) {
            //delete role permissions data
            $role->syncPermissions([]);//delete permissions data
            //delete role data
            $role->delete();
        }
    }
}
