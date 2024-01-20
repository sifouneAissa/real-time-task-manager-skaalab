<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $permissions = config('default.permissions');
        $roles = config('default.roles');
        foreach($roles as $role){

            $admin = Role::where('name','admin')->first();

            if(!$admin){
                Role::query()->create([
                    'name' => $role,
                    'guard_name' => 'web'
                ]);
            }
        }

        foreach ($permissions as $permission){
            $npermission = Permission::where('name',$permission)->first();

            if(!$npermission){
                $npermission = Permission::query()->create([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
                $admin = Role::where('name','admin')->first();
                if($admin)
                $admin->givePermissionTo($npermission->name);
            }
        }
    }
}
