<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::where(['email' => 'superadmin@stechsole.com'])->first();
        if(!$superAdmin){
            DB  ::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin@stechsole.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'phone_no' => '123456789',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
