<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('permissions')->insert([
            'name' => 'Store user',
            'slug' => 'store-user',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Update user',
            'slug' => 'update-user',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete user',
            'slug' => 'delete-user',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Store task',
            'slug' => 'store-task',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Update task',
            'slug' => 'update-task',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete task',
            'slug' => 'delete-task',
        ]);

		DB::table('permissions')->insert([
            'name' => 'Store project',
            'slug' => 'store-project',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Update project',
            'slug' => 'update-project',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete project',
            'slug' => 'delete-project',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Store document',
            'slug' => 'store-document',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete document',
            'slug' => 'delete-document',
        ]);            
    }
}
