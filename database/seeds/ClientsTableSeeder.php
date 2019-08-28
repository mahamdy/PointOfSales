<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients=['ahmed','mahmoud'];

        foreach ($clients as $client) {
            \App\Client::create([
                'name'=>$client,
                'phone'=>'0102052585',
                'address'=>'cairo',
            ]);
        }
    }
}
