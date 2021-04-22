<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $clients = [
            [
                'SiBunda Personal Access Client',
                '7nEZQGGMBLLf1WdsziFJqMuw5hEGu5ypZeyfTh9D',
                'http://localhost',
                1,
                0,
                0,
                '2021-03-27 20:41:30',
                '2021-03-27 20:41:30'],
            [
                'SiBunda Password Grant Client',
                '5xnEHfLiRiyyRGrez97iEmqzTB4h48IWr5DnHEuO',
                'http://localhost',
                0,
                1,
                0,
                '2021-03-27 20:41:30',
                '2021-03-27 20:41:30'
            ]
        ];


        DB::statement('DELETE FROM oauth_clients');
        DB::statement('ALTER SEQUENCE oauth_clients_id_seq RESTART 1');

        foreach($clients as $client) {
            DB::select('INSERT INTO
                oauth_clients(name, secret, redirect, personal_access_client, password_client, revoked, created_at, updated_at)
                VALUES(\''.$client[0].'\',\''.$client[1].'\',\''.$client[2].'\',\''.$client[3].'\',\''.$client[4].'\',\''.$client[5].'\',\''.$client[6].'\',\''.$client[7].'\')');
        }
    }
}

