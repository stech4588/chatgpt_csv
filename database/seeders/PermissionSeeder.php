<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();   //here we are disabling it so that if there are any parent/child relations
        // with other tables, it should not stop us from doing what we want or prompt any errors.

        if(Schema::hasTable('permissions')){
            DB::table('permissions')->truncate();
        }
        // this function here makes sure that after running the seeder duplication of data is ignored so if there is data
        // already in the table, first it will drop/empty the table then store latest permissions

        Schema::enableForeignKeyConstraints();

        // again enabling to maintain all relations (parent/child) will all other tables to ensure the smooth functioning

        $permissions = $this->getPermissions();   // it is a php function from php.net which returns an array.

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
    /**
     * Get the permissions data from an external source (e.g., array, configuration file).
     */
    private function getPermissions()
    {
        return [
            [
                'name' => 'viewPermissionDetails',
                'description' => 'This will allow us to see details of each permission according to their ids',
                'category' => 'permission'
            ],
            [
                'name' => 'permissionsView',
                'description' => 'This will allow to view the permissions',
                'category' => 'permission'
            ],
            [
                'name' => 'permissionsAdd',
                'description' => 'This will allow to add permissions',
                'category' => 'permission'
            ],
            [
                'name' => 'permissionsUpdate',
                'description' => 'This will allow to edit permissions',
                'category' => 'permission'
            ],
            [
                'name' => 'permissionsDelete',
                'description' => 'This will allow to delete permissions',
                'category' => 'permission'
            ],
            [
                'name' => 'roleView',
                'description' => 'This will allow to view the roles',
                'category' => 'role'
            ],
            [
                'name' => 'roleAdd',
                'description' => 'This will allow to add the roles',
                'category' => 'role'
            ],
            [
                'name' => 'roleUpdate',
                'description' => 'This will allow to update the roles',
                'category' => 'role'
            ],
            [
                'name' => 'roleDelete',
                'description' => 'This will allow to delete the roles',
                'category' => 'role'
            ],
            [
                'name' => 'normalUser',
                'description' => 'This will redirect user to store',
                'category' => 'user'
            ],
            [
                'name' => 'adminUser',
                'description' => 'This will redirect user to Admin Dashboard',
                'category' => 'user'
            ],
            [
                'name' => 'customersView',
                'description' => 'This will allow to view the customers',
                'category' => 'customers'
            ],
            [
                'name' => 'customersAdd',
                'description' => 'This will allow to add the customers',
                'category' => 'customers'
            ],
            [
                'name' => 'customersUpdate',
                'description' => 'This will allow to update the customers',
                'category' => 'customers'
            ],
            [
                'name' => 'customersDelete',
                'description' => 'This will allow to delete the customers',
                'category' => 'customers'
            ],
            [
                'name' => 'template1',
                'description' => 'This will allow to view the template',
                'category' => 'template'
            ],
            [
                'name' => 'template2',
                'description' => 'This will allow to view the template',
                'category' => 'template'
            ]
        ];
    }
}
