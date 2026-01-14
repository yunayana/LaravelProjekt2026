<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gym.pl'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'pracownik@gym.pl'],
            [
                'name' => 'Pracownik',
                'password' => Hash::make('employee123'),
            ]
        );

        $client = User::firstOrCreate(
            ['email' => 'klient@gym.pl'],
            [
                'name' => 'Klient',
                'password' => Hash::make('client123'),
            ]
        );

        $admin->roles()->syncWithoutDetaching(
            Role::where('name', 'admin')->first()
        );

        $employee->roles()->syncWithoutDetaching(
            Role::where('name', 'employee')->first()
        );

        $client->roles()->syncWithoutDetaching(
            Role::where('name', 'client')->first()
        );
    }
}
