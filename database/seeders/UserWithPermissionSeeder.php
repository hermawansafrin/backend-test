<?php

namespace Database\Seeders;

use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class UserWithPermissionSeeder extends Seeder
{
    /** @var int $administratorId */
    private int $administartorId;

    /** @var int $staffId */
    private int $staffId;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->truncateTables();// truncate all table for role and permissions

        /** reset cache role dan pemrissions */
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::beginTransaction();
        try {
            $this->createUser();//prepare for data user
            $this->createAllPermissions();//prepare for data permissions
            $this->createAdministratorAndStaffRole();//prepare for data administrator & staff
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error($e);
        }
    }

    /**
     * Create administrator and staff role
     * @return void
     */
    private function createAdministratorAndStaffRole(): void
    {
        // create administrator (super admin)
        $administrator = Role::create([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);

        $allPermissions = $this->getAllPermissions();//get all permissions

        //give all permission and administartor role to user
        $administratorRole = User::find($this->administartorId)->assignRole($administrator->name);
        foreach ($allPermissions as $permission) {
            $administratorRole->givePermissionTo($permission->name);
        }

        // create staff
        $staff = Role::create([
            'name' => 'Staff',
            'guard_name' => 'web',
        ]);

        // give all permission and staff role to user (except user_management)
        $staffRole = User::find($this->staffId)->assignRole($staff->name);
        foreach ($allPermissions as $permission) {
            if ($permission->name !== 'user_management') {
                $staffRole->givePermissionTo($permission->name);
            }
        }
    }

    /**
     * Create all permissions for all data application
     * @return void
     */
    private function createAllPermissions(): void
    {
        $allPermissions = config('permission_menu');
        $spanPosition = config('values.position_span');

        foreach ($allPermissions as $key => $parentPermission) {
            Permission::create([
                'name' => $parentPermission['permissions'],
                'guard_name' => 'web',
                'parent_id' => null,
                'is_parent' => 1,
                'position' => $this->getLastHighestPosition() + $spanPosition,
            ]);

            if ($parentPermission['childs'] !== null) {
                /** if have childs, create permissions for childs too */
                $this->createChildPermissions($parentPermission['permissions'], $parentPermission['childs']);
            }
        }
    }

    /**
     * Create child permissions for parent permission
     * @param string $parentPermission
     * @param array $childs
     */
    private function createChildPermissions(string $parentPermission, array $childs): void
    {
        $parent = Permission::whereName($parentPermission)->first();
        foreach ($childs as $key => $child) {
            Permission::create([
                'name' => $child['permissions'],
                'guard_name' => 'web',
                'parent_id' => $parent->id,
                'is_parent' => null,
                'position' => $this->getLastHighestPosition() + config('values.position_span')
            ]);

            /** jika ada lagi childs nya, rekursif kan */
            if ($child['childs'] !== null) {
                $this->createChildPermissions($child['permissions'], $child['childs']);
            }
        }
    }

    /**
     * Create user for admin and staff
     * @return void
     */
    private function createUser(): void
    {
        //id 1
        $admin = User::create([
            'name' => 'Administrator Name',
            'email' => 'administrator@mail.test',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'is_active' => 1,
        ]);
        $this->administartorId = $admin->id;

        //id 2
        $staff = User::create([
            'name' => 'Staff Name',
            'email' => 'staff@mail.test',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'is_active' => 1,
        ]);
        $this->staffId = $staff->id;
    }

    /**
     * Get last highest position for permissions
     * @return int
     */
    private function getLastHighestPosition(): int
    {
        return DB::table('permissions')->max('position') ?? 0;
    }

    /**
     * Get all permissions with model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAllPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        return Permission::get();
    }

    /**
     * Make sure for truncate-ing all table for user and role with permission
     * @return void
     */
    private function truncateTables(): void
    {
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');//must first executed
        RoleHasPermission::truncate();
        ModelHasPermission::truncate();
        ModelHasRole::truncate();
        Permission::truncate();
        Role::truncate();
        User::truncate();
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');//returned again : must last executed
    }
}
