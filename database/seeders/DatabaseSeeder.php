<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Setup Admin
        User::create([
            'name' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Simulasikan 20 members dalam 3 generasi
        // Gen 1: Kakek - Nenek
        $kakek = Member::create(['full_name' => 'Kakek Joyo', 'gender' => 'male', 'generation' => 1]);
        $nenek = Member::create(['full_name' => 'Nenek Sari', 'gender' => 'female', 'generation' => 1]);

        // Gen 2:
        $anak1 = Member::create(['full_name' => 'Budi', 'gender' => 'male', 'father_id' => $kakek->id, 'mother_id' => $nenek->id, 'generation' => 2]);
        $anak2 = Member::create(['full_name' => 'Siti', 'gender' => 'female', 'father_id' => $kakek->id, 'mother_id' => $nenek->id, 'generation' => 2]);
        $anak3 = Member::create(['full_name' => 'Tono', 'gender' => 'male', 'father_id' => $kakek->id, 'mother_id' => $nenek->id, 'generation' => 2]);

        // Gen 3: Anak dari Budi, Siti, dan Tono (Simulasi pasangannya kita lewati atau buat fiktif)
        for ($i = 1; $i <= 5; $i++) {
            Member::create(['full_name' => "Cucu Budi $i", 'gender' => ($i % 2 == 0 ? 'male' : 'female'), 'father_id' => $anak1->id, 'generation' => 3]);
        }
        for ($i = 1; $i <= 5; $i++) {
            Member::create(['full_name' => "Cucu Siti $i", 'gender' => ($i % 2 == 0 ? 'male' : 'female'), 'mother_id' => $anak2->id, 'generation' => 3]);
        }
        for ($i = 1; $i <= 5; $i++) {
            Member::create(['full_name' => "Cucu Tono $i", 'gender' => ($i % 2 == 0 ? 'male' : 'female'), 'father_id' => $anak3->id, 'generation' => 3]);
        }
    }
}
