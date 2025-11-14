<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userModelClass = config('auth.providers.users.model', 'App\\Models\\User');

        $roleNames = [
            'planner_admin',
            'planner',
            'continuous_improvement',
            'alliances_manager',
            'documents_manager',
            'conversation_manager',
            'viewer',
        ];

        $roles = [];
        foreach ($roleNames as $r) {
            $roles[$r] = Role::firstOrCreate(['name' => $r]);
        }

        // Permisos mínimos por rol del módulo estratégico
        $allPerms = Permission::all();
        $viewPerms = Permission::whereIn('name', [
            'strategic_contents.view',
            'organizations.view',
            'agreements.view',
            'strategic_documents.view',
            'conversations.view',
            'conversation_users.view',
            'messages.view',
            'message_files.view',
        ])->get();

        $plannerPerms = Permission::whereIn('name', [
            'strategic_contents.view',
            'strategic_contents.create',
            'strategic_contents.update',
            'strategic_documents.view',
            'strategic_documents.create',
            'strategic_documents.update',
            'conversations.view',
            'conversations.create',
            'conversations.update',
            'conversation_users.view',
            'conversation_users.create',
            'conversation_users.update',
            'messages.view',
            'messages.create',
            'messages.update',
            'message_files.view',
            'message_files.create',
            'message_files.update',
        ])->get();

        $alliancesPerms = Permission::whereIn('name', [
            'organizations.view',
            'organizations.create',
            'organizations.update',
            'organizations.delete',
            'agreements.view',
            'agreements.create',
            'agreements.update',
            'agreements.delete',
        ])->get();

        $documentsPerms = Permission::whereIn('name', [
            'strategic_documents.view',
            'strategic_documents.create',
            'strategic_documents.update',
            'strategic_documents.delete'
        ])->get();

        $conversationPerms = Permission::whereIn('name', [
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
        ])->get();

        $improvementPerms = Permission::whereIn('name', [
            'strategic_contents.view',
            'strategic_contents.create',
            'strategic_contents.update',
            'strategic_documents.view',
            'strategic_documents.create',
            'strategic_documents.update',
        ])->get();

        if (isset($roles['planner_admin']))
            $roles['planner_admin']->syncPermissions($allPerms);
        if (isset($roles['planner']))
            $roles['planner']->syncPermissions($plannerPerms);
        if (isset($roles['continuous_improvement']))
            $roles['continuous_improvement']->syncPermissions($improvementPerms);
        if (isset($roles['alliances_manager']))
            $roles['alliances_manager']->syncPermissions($alliancesPerms);
        if (isset($roles['documents_manager']))
            $roles['documents_manager']->syncPermissions($documentsPerms);
        if (isset($roles['conversation_manager']))
            $roles['conversation_manager']->syncPermissions($conversationPerms);
        if (isset($roles['viewer']))
            $roles['viewer']->syncPermissions($viewPerms);

        $users = [
            ['name' => 'Angel Bustamante', 'email' => 'angel.planner@incadev.com', 'roles' => ['planner_admin'], 'fullname' => 'ANGEL BUSTAMANTE PALACIOS', 'dni' => '00000028'],
            ['name' => 'Miguel Cordero', 'email' => 'miguel.planner@incadev.com', 'roles' => ['planner'], 'fullname' => 'MIGUEL CORDERO RUIZ', 'dni' => '00000029'],
            ['name' => 'Diego Briceño', 'email' => 'diego.improvement@incadev.com', 'roles' => ['continuous_improvement'], 'fullname' => 'DIEGO MAX BRICEÑO CABRERA', 'dni' => '00000030'],
            ['name' => 'Carlos Dominguez', 'email' => 'carlos.alliances@incadev.com', 'roles' => ['alliances_manager'], 'fullname' => 'CARLOS DANIEL DOMINGUEZ ALBA', 'dni' => '00000005'],
            ['name' => 'Alex Alcantara', 'email' => 'alex.docs@incadev.com', 'roles' => ['documents_manager'], 'fullname' => 'DIEGO VARGAS MUÑOZ', 'dni' => '00000006'],
            ['name' => 'Ilan Angeles', 'email' => 'ilan.conversation@incadev.com', 'roles' => ['conversation_manager'], 'fullname' => 'ILAN ANGELES RODRIGUEZ', 'dni' => '00000007'],
        ];

        foreach ($users as $u) {
            $user = $userModelClass::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'dni' => $u['dni'],
                    'fullname' => $u['fullname'],
                ]
            );
            foreach ($u['roles'] as $rolename) {
                if (isset($roles[$rolename])) {
                    $user->assignRole($roles[$rolename]);
                }
            }
        }
    }
}
