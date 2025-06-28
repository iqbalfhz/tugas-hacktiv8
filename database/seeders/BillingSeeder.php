<?php

namespace Database\Seeders;

use App\Models\Billing;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Billing::factory()->count(5)->create();
    }
}
