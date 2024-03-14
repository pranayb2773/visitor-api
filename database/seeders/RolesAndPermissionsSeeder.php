<?php

namespace Database\Seeders;

use App\Enums\Guard;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\View\Components\Info;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->permissions();
        $this->roles();

        Role::firstWhere('name', 'Receptionist')?->givePermissionTo(['view.user', 'create.user']);
        Role::firstWhere('name', 'Employee')?->givePermissionTo(['view.user', 'edit.user']);
    }

    private function permissions(): void
    {
        // create permissions
        Permission::query()->insert([
            [
                'name' => 'view.user',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to view user',
            ],
            [
                'name' => 'create.user',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to create user',
            ],
            [
                'name' => 'edit.user',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to edit user',
            ],
            [
                'name' => 'delete.user',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to delete user',
            ],
            [
                'name' => 'view.permission',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to view permission',
            ],
            [
                'name' => 'create.permission',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to create permission',
            ],
            [
                'name' => 'edit.permission',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to edit permission',
            ],
            [
                'name' => 'delete.permission',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to delete permission',
            ],
            [
                'name' => 'view.role',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to view role',
            ],
            [
                'name' => 'create.role',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to create role',
            ],
            [
                'name' => 'edit.role',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to edit role',
            ],
            [
                'name' => 'delete.role',
                'guard_name' => Guard::API->value,
                'description' => 'The permission to delete role',
            ],
        ]);

        $this->message('Permissions added successfully.');
    }

    private function roles(): void
    {
        // create roles.
        Role::query()->insert([
            [
                'name' => 'Super Admin',
                'guard_name' => Guard::API->value,
                'description' => 'The Super Administrator role grants complete and unrestricted access to all features and functionalities of the API',
            ],
            [
                'name' => 'Receptionist',
                'guard_name' => Guard::API->value,
                'description' => 'The Receptionist role grants access to managing visitors features and functionalities of the API',
            ],
            [
                'name' => 'Employee',
                'guard_name' => Guard::API->value,
                'description' => 'The Employee role grants access to some degree features and functionalities of the API',
            ],
            [
                'name' => 'Visitor',
                'guard_name' => Guard::API->value,
                'description' => 'The Visitor role grants access to some degree features and functionalities of the API',
            ],
        ]);

        $this->message('Roles added successfully.');
    }

    private function message(string $message): void
    {
        (new Info($this->command->getOutput()))->render($message);
    }
}
