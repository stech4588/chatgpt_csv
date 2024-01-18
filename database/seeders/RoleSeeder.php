<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use App\Models\Role\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();   //here we are disabling it so that if there are any parent/child relations
        if (Schema::hasTable('roles')) {
            DB::table('roles')->truncate();
        }
        Schema::enableForeignKeyConstraints();

        // again enabling to maintain all relations (parent/child) will all other tables to ensure the smooth functioning

        $roles = $this->getRoles();  // it is a php function from php.net which returns an array.

        foreach ($roles as $role) {


            Role::firstOrCreate($role);
        }
    }

    /**
     * Get the roles data from an external source (e.g., array, configuration file).
     */

    private function getRoles()
    {
        // Alternatively, you can define the roles directly in the seeder:
        return [
            [
                'name' => 'Super Admin',
                'permission_id' => [1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17],
                'description' => 'Super Admin',
            ],
            [
                'name' => 'User',
                'permission_id' => [10],
                'description' => 'Normal User',
            ],
            [
                'name' => 'ActiveUser',
                'permission_id' => [10,16,17],
                'description' => 'Active User',
            ]
        ];
    }
}
