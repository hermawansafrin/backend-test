<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $datas = [
                [
                    'name' => 'Example Customer',
                    'email' => 'example@customer.com',
                    'phone' => '081234567890',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($datas as $data) {
                Customer::insert($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error($e);
        }
    }
}
