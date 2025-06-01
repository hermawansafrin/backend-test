<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Product\ProductRepository;
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

        $data = Transaction::with([
            // for adjustment per stock
            'transaction_items:id,transaction_id,product_id,qty,price',
        ])->find($id);

        if ($data === null) {
            return null;
        }

        $data->customer_id = $input['customer_id'];
        $data->discount_percentage = (int)$input['discount_percentage'];
        $data->note = $input['note'] ?? null;
        $data->save();

        $totalWithoutDiscount = $this->deleteAndCreateTransactionItems($data->id, $input['items']);

        $totalDiscount = $totalWithoutDiscount * ($input['discount_percentage'] / 100);
        $totalAmount = $totalWithoutDiscount - $totalDiscount;

        $data->total_amount = $totalAmount;
        $data->total_without_discount = $totalWithoutDiscount;
        $data->total_discount = $totalDiscount;
        $data->save();

        /** update product stock */
        Helper::decreaseProductStock($data->id);

        return $data->id;
    }

    /**
     * Delete and create transaction items
     * @param int $transactionId
     * @param array $items
     * @return int
     */
    private function deleteAndCreateTransactionItems(int $transactionId, array $items): int
    {
        /** first, delete the current item data to update the existing stock data */
        Helper::increaseProductStock($transactionId);
        DB::table('transaction_items')->where('transaction_id', $transactionId)->delete();

        /** recreate */
        $productRepo = app(ProductRepository::class);

        $productIds = array_map(function ($item) {
            return (int)$item['product_id'];
        }, $items);

        $products = $productRepo->getByIds($productIds, true);

        $totalWithoutDiscount = 0;

        foreach ($items as $item) {
            $currentProduct = $products[$item['product_id']] ?? null;
            if ($currentProduct === null) {
                continue;
            }

            $amountPerItem = $currentProduct['price'] * $item['qty'];

            $totalWithoutDiscount += $amountPerItem;

            TransactionItem::create([
                'transaction_id' => $transactionId,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $currentProduct['price'],
                'total_amount' => $amountPerItem,
            ]);
        }

        return $totalWithoutDiscount;
    }
}
