<?php

use Illuminate\Database\Seeder;
use App\User;
use Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'role' => '2',
            'plan' => '0',
            'paid' => '1',
            'password' => Hash::make('admin1234'),
        ]);
        User::create([
            'name' => 'userone',
            'email' => 'user@mail.com',
            'role' => '1',
            'plan' => '1',
            'paid' => '1',
            'password' => Hash::make('user1234'),
        ]);
    }
}
