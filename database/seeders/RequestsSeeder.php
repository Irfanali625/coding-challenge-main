<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $senderIds = [3, 4, 5, 7, 11, 12, 15, 30, 10, 25, 27, 18, 30];

        foreach ($senderIds as $senderId) {
            Connection::create([
                'sender_id' => $senderId,
                'receiver_id' => 1,
            ]);
        }
    }
}
