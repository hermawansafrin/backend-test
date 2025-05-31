<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\StatusFlow;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            self::doSeed();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Do seeding data
     */
    private static function doSeed(): void
    {
        $static = new static();
        $products = $static->getRandomProducts();// get random data product
        $firstUser = User::first();
        $firstCustomer = Customer::first();

        // initialize total transaction
        $totalAmount = 0;
        $totalWithoutDiscount = 0;

        // generate random discount percentage (multiple of 5, between 0-50)
        $discountPercent = rand(0, 10) * 5;
        $totalDiscount = 0;

        $transaction = Transaction::create([
            'customer_id' => $firstCustomer->id,
            'status_flow_id' => StatusFlow::NEW,

            'total_amount' => $totalAmount,
            'total_without_discount' => $totalWithoutDiscount,
            'total_discount' => $totalDiscount,
            'discount_percentage' => $discountPercent,

            'note' => 'this is note of transaction',
            'created_user_id' => $firstUser->id,
        ]);

        foreach ($products as $product) {
            $randomQty = rand(1, 3);

            $currentPrice = $product->price;
            $currentTotalAmount = $currentPrice * $randomQty;

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'total_amount' => $currentTotalAmount,
                'price' => $currentPrice,
                'qty' => $randomQty,
            ]);

            //update stock product
            $product->update([
                'stock' => $product->stock - $randomQty,
            ]);

            $totalWithoutDiscount += $currentTotalAmount;// calculate for total amount of transaction
        }

        $totalDiscount = $totalWithoutDiscount * ($discountPercent / 100);
        $totalAmount = $totalWithoutDiscount - $totalDiscount;

        // update transaction data
        $transaction->update([
            'total_amount' => $totalAmount,
            'total_without_discount' => $totalWithoutDiscount,
            'total_discount' => $totalDiscount,
            'discount_percentage' => $discountPercent,
        ]);
    }

    /**
     * Get random products
     *
     * @return Collection
     */
    private function getRandomProducts(): Collection
    {
        $randomTakeData = rand(1, Product::count());
        return Product::inRandomOrder()->where('stock', '>', 0)->where('is_active', 1)->take($randomTakeData)->get();
    }
}
