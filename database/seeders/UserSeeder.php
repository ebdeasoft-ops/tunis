<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = User::create([
            'name' => 'User', 
            'email' => 'user@Admin.com',
            'password' => bcrypt('12345678'),
            'roles_name' => ["Admin"],
            'active' => 1,
            'branchs_id'=>1
            ]);
      
            $adminRole = Role::create(['name' => 'User']);

   
        $permissions = Permission::pluck('id','id')->all();
  
        $adminRole->syncPermissions($permissions);
   
        $user->assignRole('Admin');

        
    }
    }

