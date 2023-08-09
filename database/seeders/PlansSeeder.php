<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            ['name' => 'Gold Monthly', 'interval' => 'month', 'amount' => 1000],
            ['name' => 'Gold Yearly', 'interval' => 'year', 'amount' => 10000],
            ['name' => 'Silver Monthly', 'interval' => 'month', 'amount' => 500],
            ['name' => 'Silver Yearly', 'interval' => 'year', 'amount' => 5000], 
            ['name' => 'Platinum Monthly', 'interval' => 'month', 'amount' => 1500],
            ['name' => 'Platinum Yearly', 'interval' => 'year', 'amount' => 15000],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
