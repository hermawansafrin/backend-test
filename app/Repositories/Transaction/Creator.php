<?php

namespace App\Repositories\Transaction;

use App\Models\StatusFlow;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Product\ProductRepository;
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

        $data = new Transaction();

        $data->customer_id = $input['customer_id'];
        $data->discount_percentage = $input['discount_percentage'];
        $data->status_flow_id = StatusFlow::NEW;
        $data->created_user_id = auth()->user()->id;
        $data->note = $input['note'] ?? null;
        $data->save();

        $dataId = $data->id;

        /** create transaction items with get total of amount data */
        $totalWithoutDiscount = $this->createTransactionItems($dataId, $input['items']);
        $totalDiscount = $totalWithoutDiscount * ($input['discount_percentage'] / 100);
        $totalAmount = $totalWithoutDiscount - $totalDiscount;

        /** update transactions data total */
        $data->total_amount = $totalAmount;
        $data->total_without_discount = $totalWithoutDiscount;
        $data->total_discount = $totalDiscount;
        $data->save();

        /** update product stock */
        Helper::decreaseProductStock($dataId);

        return $dataId;
    }

    /**
     * Create transaction items
     * @param int $transactionId
     * @param array $items
     * @return int
     */
    private function createTransactionItems(int $transactionId, array $items): int
    {
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
