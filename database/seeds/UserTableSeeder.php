<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name'      => 'My App Admin',
            'email'     => env('ADMIN_EMAIL'),
            'password'  => Hash::make(env('ADMIN_PASS')),
            'token_key' => 'placeholderactualcsrfwillbeprovidedonfirstlogin'
        ]);

        User::create([
            'name'      => 'My App Info',
            'email'     => env('SYSTEM_EMAIL'),
            'password'  => Hash::make(env('SYSTEM_PASS')),
            'token_key' => 'placeholderactualcsrfwillbeprovidedonfirstlogin'
        ]);
    }
}
