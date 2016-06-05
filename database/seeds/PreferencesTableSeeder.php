<?php

use Illuminate\Database\Seeder;


class PreferencesTableSeeder extends Seeder
{
    public function run()
    {
        \App\Preferences::create([
            'key' => 'shippingCosts',
            'value' => '5.0',
            'type' => 'aFloat'
        ]);
    }
}
