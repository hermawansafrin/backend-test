<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
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

        $data = new Customer();
        $data->name = $input['name'];
        $data->email = $input['email'];
        $data->phone = $input['phone'];
        $data->save();

        $dataId = $data->id;

        return $dataId;
    }
}
