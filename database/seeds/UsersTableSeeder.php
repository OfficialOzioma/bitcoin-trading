<?php

use App\Model\Wallet;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'first_name'=>'Mr.',
            'last_name'=>'Admin',
            'email'=>'admin@email.com',
            'role'=>USER_ROLE_ADMIN,
            'status'=>STATUS_SUCCESS,
            'is_verified'=>1,
            'password'=>\Illuminate\Support\Facades\Hash::make('123456'),
        ]);

        User::insert([
            'first_name'=>'Mr',
            'last_name'=>'User',
            'email'=>'user@email.com',
            'role'=>USER_ROLE_USER,
            'status'=>STATUS_SUCCESS,
            'is_verified'=>1,
            'password'=>\Illuminate\Support\Facades\Hash::make('123456'),
        ]);
        Wallet::insert([
            'user_id'=>2,
            'name'=>'Default Wallet',
            'status'=>STATUS_SUCCESS,
            'is_primary'=>'1',
        ]);
        Wallet::insert([
            'user_id'=>1,
            'name'=>'Default Wallet',
            'status'=>STATUS_SUCCESS,
            'is_primary'=>'1',
        ]);
    }
}
