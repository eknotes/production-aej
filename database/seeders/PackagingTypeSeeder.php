<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackagingType;

class PackagingTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Data Kemasan: [Nama Kemasan, Unit Isi, Status]
        $packagingData = [
            ['Dus Karton A1', 'Botol', 'active'], 
            ['Dus Karton B2', 'Tutup', 'active'],
            ['Bag Plastik Besar', 'Preform', 'active'],
            ['Pallet Kayu', 'Dus', 'active'],
            ['Karton C5', 'Unit', 'inactive'],
        ];

        foreach ($packagingData as $data) {
            PackagingType::firstOrCreate(
                ['name' => $data[0]], 
                [
                    'content_unit' => $data[1],
                    'status' => $data[2],
                ]
            );
        }
    }
}