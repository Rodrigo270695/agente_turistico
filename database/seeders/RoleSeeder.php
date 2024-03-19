<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role_admin = Role::create(["name"=> "admin",]);
        $role_client = Role::create(["name"=> "cliente",]);
    }
}
