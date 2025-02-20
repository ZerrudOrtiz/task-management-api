<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            [
                'name' => 'Admin', 
                'email' => 'admin@taskmangement.ph',
                'password' => Hash::make('admin1234')
            ],
            [
                'name' => 'John Doe', 
                'email' => 'john.doe@taskmangement.ph',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Jane Smith', 
                'email' => 'jane.smith@taskmangement.ph',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Alice Johnson', 
                'email' => 'alice.johnson@taskmangement.ph',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Bob Williams', 
                'email' => 'bob.williams@taskmangement.ph',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Charlie Brown', 
                'email' => 'charlie.brown@taskmangement.ph',
                'password' => Hash::make('password123')
            ],
        ];

        foreach ($defaults as $row) {
            $existingRecord = User::where('email', $row['email'])->first();
    
            if (!$existingRecord) {
                $user = User::create($row);
            }
        }
    }
}
