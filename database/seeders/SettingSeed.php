<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate([
            'type' => 'price',
            'value' => 199.99
        ]);
    }
}
