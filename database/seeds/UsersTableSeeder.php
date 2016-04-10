<?php

use Illuminate\Database\Seeder;


class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \App\User::create([
            'name' => 'Test',
            'email' => 'test@test.ch',
            'password' => bcrypt('secret')
        ]);
    }
}
