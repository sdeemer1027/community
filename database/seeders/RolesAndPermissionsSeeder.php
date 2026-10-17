<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $roleDefinitions = [
                'super_admin' => [
                    'display_name' => 'Super Admin',
                    'description' => 'Full control over the entire platform.',
                ],
                'administrator' => [
                    'display_name' => 'Administrator',
                    'description' => 'Manages members and community operations.',
                ],
                'moderator' => [
                    'display_name' => 'Moderator',
                    'description' => 'Manages community content and reports only.',
                ],
                'member' => [
                    'display_name' => 'Member',
                    'description' => 'Standard registered community member.',
                ],
            ];

            $roles = [];

            foreach ($roleDefinitions as $name => $details) {
                $roles[$name] = Role::updateOrCreate(
                    ['name' => $name],
                    [
                        'display_name' => $details['display_name'],
                        'description' => $details['description'],
                        'is_system_role' => true,
                    ]
                );
            }

            $permissionDefinitions = [
                'members.view' => 'View member directory',
                'members.manage' => 'Manage member accounts',
                'memberships.manage' => 'Manage membership levels',
                'roles.manage' => 'Manage roles and permissions',
                'settings.manage' => 'Manage system settings',
                'integrations.manage' => 'Manage integrations and API settings',
                'posts.moderate' => 'Moderate community posts',
                'comments.moderate' => 'Moderate community comments',
                'reports.review' => 'Review and resolve community reports',
                'posts.create' => 'Create community posts',
                'comments.create' => 'Create community comments',
                'reports.submit' => 'Submit community reports',
            ];

            $permissions = [];

            foreach ($permissionDefinitions as $name => $displayName) {
                $permissions[$name] = Permission::updateOrCreate(
                    ['name' => $name],
                    ['display_name' => $displayName]
                );
            }

            $rolePermissions = [
                'super_admin' => array_keys($permissions),

                'administrator' => [
                    'members.view',
                    'members.manage',
                    'memberships.manage',
                    'posts.moderate',
                    'comments.moderate',
                    'reports.review',
                ],

                'moderator' => [
                    'posts.moderate',
                    'comments.moderate',
                    'reports.review',
                ],

                'member' => [
                    'members.view',
                    'posts.create',
                    'comments.create',
                    'reports.submit',
                ],
            ];

            foreach ($rolePermissions as $roleName => $permissionNames) {
                $roles[$roleName]->permissions()->sync(
                    collect($permissionNames)
                        ->map(fn ($name) => $permissions[$name]->id)
                        ->all()
                );
            }

            // Give each existing user the standard Member role if they
            // do not already have a role.
            User::query()
                ->whereDoesntHave('roles')
                ->each(function (User $user) use ($roles) {
                    $user->roles()->attach($roles['member']->id);
                });

            // Preserve existing super-admin protection and assign the
            // corresponding role to every existing super administrator.
            User::query()
                ->where('is_super_admin', true)
                ->each(function (User $user) use ($roles) {
                    $user->roles()->syncWithoutDetaching([
                        $roles['super_admin']->id,
                    ]);
                });
        });
    }
}
