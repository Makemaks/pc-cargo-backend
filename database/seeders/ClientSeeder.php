<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@pc-cargo.test')->firstOrFail();

        Client::insert([
            [
                'user_id' => $user->id,
                'name' => 'Acme Logistics',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Blue Ocean Freight',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Northstar Trading',
            ],
        ]);
    }
}
