<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrategicProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            OrganizationsSeeder::class,
            StrategicPermissionsSeeder::class,
            AgreementsSeeder::class,
            StrategicContentsSeeder::class,
            ConversationsSeeder::class,
            ConversationUsersSeeder::class,
            MessagesSeeder::class,
            StrategicPermissionsSeeder::class,
        ]);
    }
}
