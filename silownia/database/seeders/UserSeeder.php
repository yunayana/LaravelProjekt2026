<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\GymClass;
use App\Models\GymMembership;
use App\Models\Subscription;
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

        // Dodaj trenery
        $trainer1 = Trainer::create([
            'name' => 'Jan Kowalski',
            'email' => 'jan@gym.pl',
            'phone' => '123456789',
            'specialization' => 'CrossFit',
            'bio' => 'Doświadczony trener CrossFitu',
        ]);

        $trainer2 = Trainer::create([
            'name' => 'Maria Nowak',
            'email' => 'maria@gym.pl',
            'phone' => '987654321',
            'specialization' => 'Yoga',
            'bio' => 'Instruktor Yoga i pilates',
        ]);

        $trainer3 = Trainer::create([
            'name'           => 'Piotr Lewandowski',
            'email'          => 'piotr@gym.pl',
            'phone'          => '555111222',
            'specialization' => 'Trening siłowy',
            'bio'            => 'Specjalista od budowania siły i masy mięśniowej',
        ]);

        $trainer4 = Trainer::create([
            'name'           => 'Anna Zielińska',
            'email'          => 'anna@gym.pl',
            'phone'          => '555333444',
            'specialization' => 'Pilates / Zdrowy kręgosłup',
            'bio'            => 'Instruktorka pilatesu z doświadczeniem w pracy z osobami początkującymi',
        ]);


        // Dodaj zajęcia
        GymClass::create([
            'trainer_id' => $trainer1->id,
            'name' => 'CrossFit Basics',
            'description' => 'Podstawowy kurs CrossFitu dla początkujących',
            'schedule' => 'Poniedziałek 18:00',
            'max_participants' => 15,
        ]);

        GymClass::create([
            'trainer_id' => $trainer2->id,
            'name' => 'Yoga dla relaksu',
            'description' => 'Zajęcia relaksacyjne jogi',
            'schedule' => 'Wtorek 19:00',
            'max_participants' => 20,
        ]);

        GymClass::create([
            'trainer_id' => $trainer1->id,
            'name' => 'Trening siłowy',
            'description' => 'Zajęcia na siłowni',
            'schedule' => 'Środa 17:00',
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


        // Dodaj karnet dla klienta
        $membership = GymMembership::create([
            'user_id'         => $client->id,
            'start_date'      => now(),
            'end_date'        => now()->addMonths(3),
            'status'          => 'active',
            'membership_type' => 'standard',
        ]);

        // Dodaj subskrypcję
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
