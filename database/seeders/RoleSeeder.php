<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['id' => \App\Role::ADMIN, 'name' => 'Admin']);
        Role::create(['id' => \App\Role::USER, 'name' => 'User']);
    }
}
