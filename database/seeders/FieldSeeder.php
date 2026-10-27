<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Seed 5 realistic futsal fields with Indonesian names.
     */
    public function run(): void
    {
        $fields = [
            [
                'name' => 'Lapangan Garuda Sport',
                'type' => 'indoor',
                'price_per_hour' => 150000,
                'description' => 'Lapangan futsal indoor premium dengan rumput sintetis berkualitas tinggi dan pencahayaan LED modern. Tersedia ruang ganti dan kantin.',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Arena Futsal Merdeka',
                'type' => 'indoor',
                'price_per_hour' => 120000,
                'description' => 'Lapangan futsal indoor dengan fasilitas lengkap, AC sentral, dan tribun penonton. Cocok untuk turnamen dan latihan rutin.',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'GOR Nusantara',
                'type' => 'outdoor',
                'price_per_hour' => 80000,
                'description' => 'Lapangan futsal outdoor dengan lantai beton berstandar nasional. Dilengkapi lampu stadion untuk bermain malam hari.',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Sport Center Bintang Jaya',
                'type' => 'indoor',
                'price_per_hour' => 175000,
                'description' => 'Lapangan futsal indoor VIP dengan parket kayu berkualitas internasional. Dilengkapi dengan papan skor digital dan sistem audio.',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Lapangan Merah Putih',
                'type' => 'outdoor',
                'price_per_hour' => 100000,
                'description' => 'Lapangan futsal outdoor dengan rumput sintetis generasi terbaru. Lokasi strategis di pusat kota dengan parkir luas.',
                'image_path' => null,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            Field::create($field);
        }
    }
}
