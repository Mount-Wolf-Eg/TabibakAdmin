<?php

namespace Database\Seeders;

use App\Constants\RoleNameConstants;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultRoles = RoleNameConstants::values();
        foreach ($defaultRoles as $defaultRole){
            $role = Role::findOrCreate($defaultRole);
            $role->update(['can_be_deleted' => true]);

            if ($role->name == RoleNameConstants::ADMIN->value){
                $permissions = Permission::all();
                $role->syncPermissions($permissions);
            }

            if ($role->name == RoleNameConstants::DOCTOR->value){
                $doctorPermissions = ['create-article', 'update-article', 'delete-article'];
                $permissions = Permission::whereIn('name', $doctorPermissions)->get();
                $role->syncPermissions($permissions);
            }

            if ($role->name == RoleNameConstants::PATIENT->value){
                $patientPermissions = ['read-article', 'create-rate', 'edit-rate', 'delete-rate',
                    'read-rate', 'create-complaint', 'edit-complaint', 'delete-complaint', 'read-complaint'];
                $permissions = Permission::whereIn('name', $patientPermissions)->get();
                $role->syncPermissions($permissions);
            }

            if ($role->name == RoleNameConstants::VENDOR->value){
                // $vendorPermissions = ['read-consultation'];
                $vendorPermissions = ['read-referral'];
                $permissions = Permission::whereIn('name', $vendorPermissions)->get();
                $role->syncPermissions($permissions);
            }
        }

    }
}
