<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conversation_users')->insert([
            ['conversation_id' => 1, 'user_id' => 2],
            ['conversation_id' => 1, 'user_id' => 5],
            ['conversation_id' => 2, 'user_id' => 3],
            ['conversation_id' => 2, 'user_id' => 6],
            ['conversation_id' => 3, 'user_id' => 2],
            ['conversation_id' => 3, 'user_id' => 4],
        ]);
    }
}
