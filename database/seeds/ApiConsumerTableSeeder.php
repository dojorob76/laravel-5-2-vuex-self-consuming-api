<?php

use Illuminate\Database\Seeder;
use App\ApiConsumer;

class ApiConsumerTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_consumers')->delete();

        /**
         * Create the Admin Super API Consumer
         */
        ApiConsumer::create([
            'email'     => env('ADMIN_EMAIL'),
            'api_token' => Hash::make(env('ADMIN_ACCESS_TOKEN')),
            'level'     => 9
        ]);

        /**
         * Create the System Super API Consumer
         */
        ApiConsumer::create([
            'email'     => env('SYSTEM_EMAIL'),
            'api_token' => Hash::make(env('SYSTEM_ACCESS_TOKEN')),
            'level'     => 9
        ]);
    }
}
