<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'telegram_bot',
            'messenger_bot',
            'website_widget',
        ];
        $sharedPermissions = [
            'product',
            'categories',
            'order',
            'settings',
            'faq',
            'contact_us',
        ];
        $superAdmin = Role::create(['name' => 'super-admin']);
        $storeOwner = Role::create(['name' => 'store-owner']);

        foreach ($sharedPermissions as $permission)
            $storeOwner->givePermissionTo(Permission::create(['name' => $permission]));
        foreach ($permissions as $permission)
            Permission::create(['name' => $permission]);

    }
}
