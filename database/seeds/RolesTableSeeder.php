<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'User',
            'slug' => 'user',
        ]);

        DB::table('roles')->insert([
            'name' => 'Member',
            'slug' => 'member',
        ]);

        DB::table('roles')->insert([
            'name' => 'Manager',
            'slug' => 'manager',
        ]);

        DB::table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
    }
}
