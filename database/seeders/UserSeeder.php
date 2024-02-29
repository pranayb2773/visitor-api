<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserTypes;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\View\Components\Info;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->superAdmin();
        $this->internalUsers();
        $this->externalUsers();
    }

    private function superAdmin(): void
    {
        $superAdmin = User::create([
            'first_name' => 'Pranay',
            'last_name' => 'Baddam',
            'email' => 'pranay.teja.baddam@gmail.com',
            'password' => 'Baddam@#6',
            'type' => UserTypes::INTERNAL,
            'status' => UserStatus::ACTIVE
        ]);

        $superAdmin->assignRole(Role::superAdmin()->first()->name);

        $this->message('Super Admin user added successfully.');
    }

    private function internalUsers(): void
    {
        $roles = Role::whereIn('name', ['Receptionist', 'Employee'])->pluck('name')->toArray();

        if (empty($roles)) {
            User::factory(20)->internal()->active()->create();
        }

        User::factory(20)->internal()->active()->create()->each(function (User $user) use ($roles) {
            $user->assignRole($roles);
        });

        $this->message('Internal users added successfully.');
    }

    private function externalUsers(): void
    {
        $roles = Role::whereIn('name', ['Visitor'])->pluck('name')->toArray();

        if (empty($roles)) {
            User::factory(200)->external()->create();
        }

        User::factory(200)->external()->create()->each(function (User $user) use ($roles) {
            $user->assignRole($roles);
        });

        $this->message('External users added successfully.');
    }

    private function message(string $message): void
    {
        (new Info($this->command->getOutput()))->render($message);
    }
}
