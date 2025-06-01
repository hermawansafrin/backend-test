<?php

namespace App\Repositories\Transaction;

use App\Models\StatusFlow;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Class for delete data process
 */
class Canceller
{
    /** @var int */
    private int $id;

    /** @var array */
    private array $option = [];

    /** @var bool */
    private bool $usingDbTransaction = true;

    /**
     * Perform preparation process before cancel
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
     * Perform cancel process with transaction check
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
        $data->status_flow_id = StatusFlow::CANCELLED;
        $data->last_updated_user_id = auth()->user()->id;
        $data->paid_date_time = null;//make sure there is no paid date cause canceled
        $data->save();

        //increase product stock cause canceling data
        Helper::increaseProductStock($id);

        return $data->id;
    }
}
