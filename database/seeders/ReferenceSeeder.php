<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeders = [
            StatusFlowSeeder::class,
        ];

        collect($seeders)->each(function ($class) {
            echo "\033[34m{$class}..\033[0m\n"; //using like this for coloring, cause call with silent
            $this->command->callSilent('db:seed', [
                '--class' => $class
            ]);
        });
    }
}
