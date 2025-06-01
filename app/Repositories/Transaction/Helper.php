<?php

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\DB;

class Helper
{
    /**
     * increasing product stock cause cancel order or delete order
     * @param int $transactionId
     * @return void
     */
    public static function increaseProductStock(int $transactionId): void
    {
        $transactionItems = DB::table('transaction_items')
            ->select([
                'transaction_items.id as id',
                'transaction_items.product_id as product_id',
                'transaction_items.qty as qty',
            ])
            ->where('transaction_items.transaction_id', $transactionId)
            ->get();

        foreach ($transactionItems as $transactionItem) {
            DB::table('products')
                ->where('id', $transactionItem->product_id)
                ->update([
                    'stock' => DB::raw("stock + {$transactionItem->qty}"),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * decreasing product stock cause create order order
     * @param int $transactionId
     * @return void
     */
    public static function decreaseProductStock(int $transactionId): void
    {
        $transactionItems = DB::table('transaction_items')
            ->select([
                'transaction_items.id as id',
                'transaction_items.product_id as product_id',
                'transaction_items.qty as qty',
            ])
            ->where('transaction_items.transaction_id', $transactionId)
            ->get();

        foreach ($transactionItems as $transactionItem) {
            DB::table('products')
                ->where('id', $transactionItem->product_id)
                ->update([
                    'stock' => DB::raw("stock - {$transactionItem->qty}"),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * update product stock
     * @param int $productId
     * @param int $qty
     * @param bool $isAddition
     * @return void
     */
    public static function updateProductStock(int $productId, int $qty, bool $isAddition): void
    {
        $action = $isAddition ? '+' : '-';

        DB::table('products')
            ->where('id', $productId)
            ->update(['stock' => DB::raw("stock {$action} {$qty}"), 'updated_at' => now()]);
    }
}
