<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gym.pl',
            'password' => Hash::make('admin123'),
        ]);

        $employee = User::create([
            'name' => 'Pracownik',
            'email' => 'employee@gym.pl',
            'password' => Hash::make('employee123'),
        ]);

        $client = User::create([
            'name' => 'Klient',
            'email' => 'client@gym.pl',
            'password' => Hash::make('client123'),
        ]);

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
