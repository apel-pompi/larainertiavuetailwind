<?php

namespace Database\Seeders;

use App\Models\PermissionGroupModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $admin = User::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->CreateSuperAdminRole($admin);
        // Create and Assign Permissions
        $groupName = PermissionGroupModel::all();
        foreach ($groupName as $value) {
            $permissions = [
                [
                    'group_name' => $value['id'],
                    'permissions' => [
                        strtolower($value['name']).'.'.'index',
                        strtolower($value['name']).'.'.'create',
                        strtolower($value['name']).'.'.'store',
                        strtolower($value['name']).'.'.'edit',
                        strtolower($value['name']).'.'.'update',
                        strtolower($value['name']).'.'.'destroy',
                    ],
                ],
                
            ];
            for ($i = 0; $i < count($permissions); $i++) {
                $permissionGroup = $permissions[$i]['group_name'];
                for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                    $permissionExist = Permission::where('name', $permissions[$i]['permissions'][$j])->first();
                    if (is_null($permissionExist)) {
                        $permission = Permission::create(
                            [
                                'name' => $permissions[$i]['permissions'][$j],
                                'group_id' => $permissionGroup,
                                'guard_name' => 'web'
                            ]
                        );
                        $roleSuperAdmin->givePermissionTo($permission);
                        $permission->assignRole($roleSuperAdmin);
                    }
                }
            }
            // Assign super admin role permission to superadmin user
            if ($admin) {
                $admin->assignRole($roleSuperAdmin);
            }
        }
    }

    private function CreateSuperAdminRole($admin): Role
    {
        if (is_null($admin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        }else{
            $roleSuperAdmin = Role::where('name', 'superadmin')->where('guard_name', 'web')->first();
        }

        if (is_null($roleSuperAdmin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        }

        return $roleSuperAdmin;
    }
}
