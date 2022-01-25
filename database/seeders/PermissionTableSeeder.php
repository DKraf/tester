<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'position-list',
            'position-create',
            'position-edit',
            'position-delete',
            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
            'testtype-list',
            'testtype-create',
            'testtype-edit',
            'testtype-delete',
            'testtheme-delete',
            'testtheme-create',
            'testtheme-edit',
            'testtheme-list',
            'test-delete',
            'test-edit',
            'test-create',
            'test-list',
            'assigntest-list',
            'assigntest-create',
            'assigntest-edit',
            'assigntest-delete',
            'homepage-list',
            'homepage-edit'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
