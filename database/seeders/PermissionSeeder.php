<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions to avoid stale cache during seeding
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $guard = 'api';

        $permissions = [
            // images
            'images.create', 'images.read', 'images.update', 'images.delete',
            // image-intros
            'image-intros.create', 'image-intros.read', 'image-intros.update', 'image-intros.delete', 'image-intros.publish',
            // topics
            'topics.create', 'topics.read', 'topics.update', 'topics.delete', 'topics.order',
            // videos
            'videos.create', 'videos.read', 'videos.update', 'videos.delete', 'videos.upload', 'videos.publish',
            // posts
            'posts.create', 'posts.read', 'posts.update', 'posts.delete', 'posts.publish',
            // papers
            'papers.create', 'papers.read', 'papers.update', 'papers.delete', 'papers.publish',
            // backups
            'backups.read', 'backups.create', 'backups.restore', 'backups.delete',
        ];

        foreach ($permissions as $name) {
            Permission::query()->firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);
        }

        $admin = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $editor = Role::query()->firstOrCreate(['name' => 'editor', 'guard_name' => $guard]);
        $researcher = Role::query()->firstOrCreate(['name' => 'researcher', 'guard_name' => $guard]);
        $viewer = Role::query()->firstOrCreate(['name' => 'viewer', 'guard_name' => $guard]);

        // assign permissions to roles
        $admin->syncPermissions(Permission::all());

        $editorPerms = [
            'image-intros.create', 'image-intros.read', 'image-intros.update', 'image-intros.delete', 'image-intros.publish',
            'posts.create', 'posts.read', 'posts.update', 'posts.delete', 'posts.publish',
            'videos.create', 'videos.read', 'videos.update', 'videos.delete', 'videos.upload', 'videos.publish',
        ];
        $editor->syncPermissions(Permission::query()->whereIn('name', $editorPerms)->get());

        $researcherPerms = [
            'papers.create', 'papers.read', 'papers.update',
            'image-intros.create', 'image-intros.read', 'image-intros.update',
            'videos.create', 'videos.read', 'videos.update',
        ];
        $researcher->syncPermissions(Permission::query()->whereIn('name', $researcherPerms)->get());

        $viewerPerms = [
            'images.read', 'image-intros.read', 'topics.read', 'videos.read', 'posts.read', 'papers.read',
        ];
        $viewer->syncPermissions(Permission::query()->whereIn('name', $viewerPerms)->get());
    }
}


