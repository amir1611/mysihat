<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $expertises = [
            'Pediatrician', 'Cardiologist', 'Neurologist', 'Orthopedic Surgeon', 'Gynecologist',
            'Radiologist', 'Oncologist', 'Surgeon', 'Anesthesiologist', 'Gastroenterologist',
            'Dermatologist', 'Endocrinologist', 'Nephrologist', 'Urologist', 'Pulmonologist',
            'Ophthalmologist', 'Otolaryngologist', 'Hematologist', 'Pathologist', 'Psychiatrist',
            'Infectious Disease Specialist', 'Physiotherapist', 'Intensivist', 'Neonatologist', 'Geriatrician',
            'Dietitian', 'Pharmacist', 'Rheumatologist', 'Plastic Surgeon', 'Palliative Care Specialist',
            'Burn Specialist', 'Rehabilitation Specialist'
        ];

        $malaysianNames = [
            'Amirul', 'Farah', 'Zain', 'Nurul', 'Ismail', 'Siti', 'Azman', 'Lina', 'Hafiz', 'Yasmin',
            'Rizal', 'Aishah', 'Khairul', 'Nadia', 'Aziz', 'Fatimah', 'Razak', 'Zara', 'Imran', 'Aina',
            'Danial', 'Elina', 'Firdaus', 'Hana', 'Ibrahim', 'Jamilah', 'Kamal', 'Laila', 'Mazlan', 'Nora',
            'Omar', 'Puteri', 'Qasim', 'Raihana', 'Sulaiman', 'Tasha', 'Umar', 'Vina', 'Wafi', 'Xena',
            'Yusof', 'Zahra', 'Adam', 'Batrisyia', 'Chong', 'Dania', 'Ezra', 'Faizal', 'Goh', 'Hidayah',
            'Irfan', 'Jasmine', 'Kamil', 'Lily', 'Mustafa', 'Naomi', 'Osman', 'Priya', 'Qayyum', 'Rania',
            'Syed', 'Taufik', 'Ummi', 'Vivian'
        ];

        shuffle($malaysianNames);

        foreach ($expertises as $expertise) {
            for ($i = 1; $i <= 2; $i++) {
                $name = 'Dr. ' . array_pop($malaysianNames);
                $gender = ['male', 'female'][rand(0, 1)];
                
                $icNumber = $this->generateUniqueIcNumber();
                $email = $this->generateUniqueEmail($name);

                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'ic_number' => $icNumber,
                        'password' => bcrypt('1234'),
                        'gender' => $gender,
                        'date_of_birth' => Carbon::now()->subYears(rand(30, 60))->format('Y-m-d'),
                        'phone_number' => '60' . rand(100000000, 199999999),
                        'type' => 2, // Doctor type
                        'medical_license_number' => 'MMC' . rand(10000, 99999),
                        'expertise' => $expertise,
                        'email_verified_at' => Carbon::now(),
                    ]
                );
            }
        }
    }

    /**
     * Generate a unique IC number.
     *
     * @return string
     */
    private function generateUniqueIcNumber(): string
    {
        do {
            $icNumber = rand(600101, 991231) . '-' . rand(10, 14) . '-' . rand(1000, 9999);
        } while (User::where('ic_number', $icNumber)->exists());

        return $icNumber;
    }

    /**
     * Generate a unique email.
     *
     * @param string $name
     * @return string
     */
    private function generateUniqueEmail(string $name): string
    {
        $baseName = strtolower(explode(' ', $name)[1]); 
        $baseEmail = $baseName . '@mysihat.com';
        $email = $baseEmail;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $baseName . $counter . '@mysihat.com';
            $counter++;
        }

        return $email;
    }
}
