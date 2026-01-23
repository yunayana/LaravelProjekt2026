<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\GymClass;
use App\Models\GymMembership;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // podstawowi użytkownicy
        $admin = User::firstOrCreate(
            ['email' => 'admin@gym.pl'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'employee@gym.pl'],
            [
                'name'     => 'Pracownik',
                'password' => Hash::make('employee123'),
            ]
        );

        $client = User::firstOrCreate(
            ['email' => 'client@gym.pl'],
            [
                'name'     => 'Klient',
                'password' => Hash::make('client123'),
            ]
        );

        // przypisanie ról (Spatie)
        $admin->assignRole('admin');
        $employee->assignRole('trainer');
        $client->assignRole('client');

        // rola trenera
        $trainerRole = Role::where('name', 'trainer')->first();

        // trenerzy jako users
        $trainer1 = User::firstOrCreate(
            ['email' => 'jan@gym.pl'],
            [
                'name'     => 'Jan Kowalski',
                'password' => bcrypt('password'),
                'photo' => 'trainers/jan.png',
                'specialization' => 'CrossFit',
                'bio' => 'Doświadczony trener CrossFitu',
            ]
        );
        $trainer1->assignRole($trainerRole);

        $trainer2 = User::firstOrCreate(
            ['email' => 'maria@gym.pl'],
            [
                'name'     => 'Maria Nowak',
                'password' => bcrypt('password'),
                'photo' => 'trainers/maria.png',
                'specialization' => 'Joga',
                'bio' => 'Certyfikowany instruktor jogi',
            ]
        );
        $trainer2->assignRole($trainerRole);

        $trainer3 = User::firstOrCreate(
            ['email' => 'piotr@gym.pl'],
            [
                'name'     => 'Piotr Wiśniewski',
                'password' => bcrypt('password'),
                'photo' => 'trainers/piotr.png',
                'specialization' => 'Trening siłowy',
                'bio' => 'Specjalista od treningu siłowego',
            ]
        );
        $trainer3->assignRole($trainerRole);

        $trainer4 = User::firstOrCreate(
            ['email' => 'anna@gym.pl'],
            [
                'name'     => 'Anna Zielińska',
                'password' => bcrypt('password'),
                'photo' => 'trainers/anna.png',
                'specialization' => 'Pilates',
                'bio' => 'Instruktor pilates z wieloletnim doświadczeniem',
            ]
        );
        $trainer4->assignRole($trainerRole);

        // zajęcia
        GymClass::create([
            'trainer_id'       => $trainer1->id,
            'name'             => 'CrossFit Basics',
            'description'      => 'Podstawowy kurs CrossFitu dla początkujących',
            'schedule'         => 'Poniedziałek 18:00',
            'max_participants' => 15,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer2->id,
            'name'             => 'Yoga dla relaksu',
            'description'      => 'Zajęcia relaksacyjne jogi',
            'schedule'         => 'Wtorek 19:00',
            'max_participants' => 20,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer1->id,
            'name'             => 'Trening siłowy',
            'description'      => 'Zajęcia na siłowni',
            'schedule'         => 'Środa 17:00',
            'max_participants' => 10,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer1->id,
            'name'             => 'HIIT Spalanie',
            'description'      => 'Intensywny trening interwałowy dla średniozaawansowanych',
            'schedule'         => 'Czwartek 18:30',
            'max_participants' => 20,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer2->id,
            'name'             => 'Stretching & Mobility',
            'description'      => 'Rozciąganie i mobilność dla wszystkich poziomów',
            'schedule'         => 'Piątek 17:00',
            'max_participants' => 25,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer3->id,
            'name'             => 'Full Body Workout',
            'description'      => 'Ogólnorozwojowy trening całego ciała',
            'schedule'         => 'Sobota 10:00',
            'max_participants' => 18,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer4->id,
            'name'             => 'Zdrowy kręgosłup',
            'description'      => 'Zajęcia wzmacniające mięśnie posturalne i odciążające kręgosłup',
            'schedule'         => 'Czwartek 17:00',
            'max_participants' => 16,
        ]);

        GymClass::create([
            'trainer_id'       => $trainer3->id,
            'name'             => 'Trening obwodowy',
            'description'      => 'Intensywny trening na stacjach dla osób średniozaawansowanych',
            'schedule'         => 'Wtorek 18:30',
            'max_participants' => 14,
        ]);

        // karnet dla klienta
        $membership = GymMembership::create([
            'user_id'         => $client->id,
            'start_date'      => now(),
            'end_date'        => now()->addMonths(3),
            'status'          => 'active',
            'membership_type' => 'standard',
        ]);

        // subskrypcja
        Subscription::create([
            'user_id'           => $client->id,
            'gym_membership_id' => $membership->id,
            'plan_name'         => 'Plan Standardowy',
            'price'             => 99.99,
            'duration_months'   => 3,
            'start_date'        => now(),
            'end_date'          => now()->addMonths(3),
            'active'            => true,
        ]);
    }
}
