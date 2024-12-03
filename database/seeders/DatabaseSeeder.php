<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Notification;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Symfony\Component\Uid\Ulid;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Isi data pada tabel roles
        $roles = [
            [
                'id' => Ulid::generate(),
                'name' => 'OWNER',
                'description' => 'Company Owner',
            ],

            [
                'id' => Ulid::generate(),
                'name' => 'Pending Member',
                'description' => 'Pending Member',
            ],

            [
                'id' => Ulid::generate(),
                'name' => 'Employee',
                'description' => 'Employee',
            ],
        ];

        // Insert data ke database
        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['name' => 'Manage User Permission', 'description' => 'Manage User Permission'],
            ['name' => 'Read User permission', 'description' => 'Read User permission'],
            ['name' => 'Edit Company', 'description' => 'Edit Company'],
            ['name' => 'Delete Company', 'description' => 'Delete Company'],
            ['name' => 'Add Company User', 'description' => 'Add Company User'],
            ['name' => 'Read Company User', 'description' => 'Read Company User'],
            ['name' => 'Edit Company User', 'description' => 'Edit Company User'],
            ['name' => 'Delete Company User', 'description' => 'Delete Company User'],
            ['name' => 'Read Pending Company User', 'description' => 'Read Pending Company User'],
            ['name' => 'Update Pending Company User', 'description' => 'Update Pending Company User'],
            ['name' => 'Acc Company User', 'description' => 'Acc Company User'],
            ['name' => 'Read Company Role', 'description' => 'Read Company Role'],
            ['name' => 'Add Company Role', 'description' => 'Add Company Role'],
            ['name' => 'Edit Company Role', 'description' => 'Edit Company Role'],
            ['name' => 'Delete Company Role', 'description' => 'Delete Company Role'],
            ['name' => 'Add Category Technology', 'description' => 'Add Category Technology'],
            ['name' => 'Read Category Technology', 'description' => 'Read Category Technology'],
            ['name' => 'Edit Category Technology', 'description' => 'Edit Category Technology'],
            ['name' => 'Delete Category Technology', 'description' => 'Delete Category Technology'],
            ['name' => 'Add Technology', 'description' => 'Add Technology'],
            ['name' => 'Read Technology', 'description' => 'Read Technology'],
            ['name' => 'Edit Technology', 'description' => 'Edit Technology'],
            ['name' => 'Delete Technology', 'description' => 'Delete Technology'],
            ['name' => 'Read Change Log', 'description' => 'Read Change Log'],
            ['name' => 'Delete Log', 'description' => 'Delete Log'],
            ['name' => 'Read Company Profile', 'description' => 'Read Company Profile'],
            ['name' => 'Leaving The Company', 'description' => 'Leaving The Company'],
            ['name' => 'Waiting', 'description' => 'Waiting Acc from Owner'],


        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'id' => Ulid::generate(), // Generate a new ULID
                'name' => $permission['name'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    
        
    }
}
