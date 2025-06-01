<?php

namespace App\Repositories\Transaction;

use App\Models\StatusFlow;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Class for delete data process
 */
class Completer
{
    /** @var int */
    private int $id;

    /** @var array */
    private array $input = [];

    /** @var bool */
    private bool $usingDbTransaction = true;

    /**
     * Perform preparation process before complete
     * @param int $id
     * @param array $input
     * @return self
     */
    public function prepare(int $id, array $input): self
    {
        if (isset($input['using_db_transaction'])) {
            $this->usingDbTransaction = $input['using_db_transaction'] ?? true;
            unset($input['using_db_transaction']);
        }

        $this->id = $id;
        $this->input = $input;

        return $this;
    }

    /**
     * Perform complete process with transaction check
     * @return void
     */
    public function execute(): void
    {
        $usingDbTransaction = $this->usingDbTransaction;
        if ($usingDbTransaction) {//use this if there was a previous transaction and commit process (starting new transaction)
            DB::beginTransaction();
            try {
                $this->doCancel();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {//use this if there was a previous transaction and no commit process yet (one transaction sequence)
            $this->doCancel();
        }
    }

    /**
     * Perform cancel process
     * @return int
     */
    public function doCancel(): int
    {
        $id = $this->id;
        $dataId = null;
        $data = Transaction::find($id);
        $data->status_flow_id = StatusFlow::COMPLETED;
        $data->last_updated_user_id = auth()->user()->id;
        $data->paid_date_time = $this->input['paid_date_time'];
        $data->save();

        return $data->id;
    }
}
