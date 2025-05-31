<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
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
        $id = $this->id;
        $input = $this->input;

        $data = Customer::find($id);
        if ($data === null) {
            return null;
        }

        $data->name = $input['name'];
        $data->email = $input['email'];
        $data->phone = $input['phone'];
        $data->save();

        return $data->id;
    }
}
