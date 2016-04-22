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

        // Create the Admin User
        User::create([
            'name'      => env('SITE_NAME') . ' Admin',
            'email'     => env('ADMIN_EMAIL'),
            'password'  => Hash::make(env('ADMIN_PASS')),
            'token_key' => 'placeholderactualcsrfwillbeprovidedonfirstlogin'
        ]);

        // Create the System User
        User::create([
            'name'      => env('SITE_NAME') . ' Info',
            'email'     => env('SYSTEM_EMAIL'),
            'password'  => Hash::make(env('SYSTEM_PASS')),
            'token_key' => 'placeholderactualcsrfwillbeprovidedonfirstlogin'
        ]);
    }
}
