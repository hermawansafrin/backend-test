<?php

namespace Database\Seeders;

use App\Models\StatusFlow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusFlow::truncate();//truncate first

        DB::beginTransaction();
        try {
            $datas = [
                [
                    'id' => StatusFlow::NEW,
                    'name' => 'New',
                ],
                [
                    'id' => StatusFlow::COMPLETED,
                    'name' => 'Completed',
                ],
                [
                    'id' => StatusFlow::CANCELLED,
                    'name' => 'Cancelled',
                ],
            ];

            StatusFlow::insert($datas);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error($e);
        }
    }
}
