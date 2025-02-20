<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaulUserSeeder extends Seeder
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
        ];

        foreach ($defaults as $row) {
            $existingRecord = User::where('email', $row['email'])->first();
    
            if (!$existingRecord) {
                $user = User::create($row);
            }
        }
    }
}
