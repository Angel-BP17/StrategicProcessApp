<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class StrategicPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'strategic_contents.view',
            'strategic_contents.create',
            'strategic_contents.update',
            'strategic_contents.delete',
            'organizations.view',
            'organizations.create',
            'organizations.update',
            'organizations.delete',
            'agreements.view',
            'agreements.create',
            'agreements.update',
            'agreements.delete',
            'strategic_documents.view',
            'strategic_documents.create',
            'strategic_documents.update',
            'strategic_documents.delete',
            'conversations.view',
            'conversations.create',
            'conversations.update',
            'conversations.delete',
            'conversation_users.view',
            'conversation_users.create',
            'conversation_users.update',
            'conversation_users.delete',
            'messages.view',
            'messages.create',
            'messages.update',
            'messages.delete',
            'message_files.view',
            'message_files.create',
            'message_files.update',
            'message_files.delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }
    }
}
