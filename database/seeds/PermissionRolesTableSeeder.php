<?php

use App\Role;
use Illuminate\Database\Seeder;

class PermissionRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Role::find(4)->permissions()->attach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);

        Role::find(3)->permissions()->attach([4, 5, 6, 7, 8, 9, 10, 11]);

        Role::find(2)->permissions()->attach([4, 5, 6, 10, 11]);

    	        
    }
}
