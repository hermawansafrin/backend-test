<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\StatusFlow;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
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
        $jan = self::createRandomTransaction('2025-01-01 00:00:00', StatusFlow::NEW);
        $jan = self::createRandomTransaction('2025-01-01 00:00:01', StatusFlow::COMPLETED);
        $jan = self::createRandomTransaction('2025-01-01 00:00:02', StatusFlow::CANCELLED);

        $feb = self::createRandomTransaction('2025-02-01 00:00:00', StatusFlow::NEW);
        $feb = self::createRandomTransaction('2025-02-01 00:00:01', StatusFlow::COMPLETED);
        $feb = self::createRandomTransaction('2025-02-01 00:00:02', StatusFlow::CANCELLED);

        $mar = self::createRandomTransaction('2025-03-01 00:00:00', StatusFlow::NEW);
        $mar = self::createRandomTransaction('2025-03-01 00:00:01', StatusFlow::COMPLETED);
        $mar = self::createRandomTransaction('2025-03-01 00:00:02', StatusFlow::CANCELLED);

        $apr = self::createRandomTransaction('2025-04-01 00:00:00', StatusFlow::NEW);
        $apr = self::createRandomTransaction('2025-04-01 00:00:01', StatusFlow::COMPLETED);
        $apr = self::createRandomTransaction('2025-04-01 00:00:02', StatusFlow::CANCELLED);

        $may = self::createRandomTransaction('2025-05-01 00:00:00', StatusFlow::NEW);
        $may = self::createRandomTransaction('2025-05-01 00:00:01', StatusFlow::COMPLETED);
        $may = self::createRandomTransaction('2025-05-01 00:00:02', StatusFlow::CANCELLED);

        $jun = self::createRandomTransaction('2025-06-01 00:00:00', StatusFlow::NEW);
        $jun = self::createRandomTransaction('2025-06-01 00:00:01', StatusFlow::COMPLETED);
        $jun = self::createRandomTransaction('2025-06-01 00:00:02', StatusFlow::CANCELLED);
    }

    /**
     * Create random transaction
     *
     * @param string $createdAt
     * @param int $statusFlowId
     * @return void
     */
    private static function createRandomTransaction(string $createdAt, int $statusFlowId): void
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
            'status_flow_id' => $statusFlowId,

            'total_amount' => $totalAmount,
            'total_without_discount' => $totalWithoutDiscount,
            'total_discount' => $totalDiscount,
            'discount_percentage' => $discountPercent,
            'paid_date_time' => $statusFlowId === StatusFlow::COMPLETED ? Carbon::parse($createdAt)->addHours(2)->toDateTimeString() : null,

            'note' => 'this is note of transaction',
            'created_at' => $createdAt,
            'created_user_id' => $firstUser->id,
        ]);

        foreach ($products as $product) {
            $randomQty = rand(1, 5);

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
