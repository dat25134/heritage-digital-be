<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update admin user
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
            ]
        );
        $admin->assignRole('admin');

        // Editor
        $editor = User::query()->firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor',
                'password' => 'password',
            ]
        );
        $editor->assignRole('editor');

        // Researcher
        $researcher = User::query()->firstOrCreate(
            ['email' => 'researcher@example.com'],
            [
                'name' => 'Researcher',
                'password' => 'password',
            ]
        );
        $researcher->assignRole('researcher');

        // Viewer
        $viewer = User::query()->firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer',
                'password' => 'password',
            ]
        );
        $viewer->assignRole('viewer');
    }
}


