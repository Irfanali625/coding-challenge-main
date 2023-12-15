<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectionsInCommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $senderIds = [1, 4, 1, 1, 11, 2, 13, 10, 14, 1, 1, 1];
        $receiverIds = [4, 11, 10, 11, 13, 15, 14, 10, 4, 13, 15, 10];

        foreach ($senderIds as $senderId) {
            Connection::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverIds,
                'accepted' => 1
            ]);
        }
    }
}
