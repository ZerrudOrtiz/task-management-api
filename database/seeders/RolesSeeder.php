<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'admin',
            'employee'
        ];

        foreach ($defaults as $row) {
            $existingRecord = Role::where('name', $row)->first();
    
            if (!$existingRecord) {
                Role::create(['name' => $row]);
            }
        }
    }
}
