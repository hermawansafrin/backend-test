<?php

namespace App\Repositories\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->is_active = (int) $input['is_active'];
        $user->save();

        $role = Role::findOrFail((int) $input['role_id']);
        $user->assignRole($role);

        $dataId = $user->id;

        return $dataId;
    }
}
