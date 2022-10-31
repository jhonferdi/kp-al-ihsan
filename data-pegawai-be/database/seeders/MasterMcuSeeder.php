<?php

namespace Database\Seeders;

use App\Models\MasterMcu;
use Illuminate\Database\Seeder;

class MasterMcuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama_penyakit' => 'Pernah Positif COVID-19',
                'kategori' => 'mcu',
                'order' => 1,
            ],
            [
                'nama_penyakit' => 'Hipertensi',
                'kategori' => 'mcu',
                'order' => 2,
            ],
            [
                'nama_penyakit' => 'Diabetes Melilus',
                'kategori' => 'mcu',
                'order' => 3,
            ],
            [
                'nama_penyakit' => 'Penyakit Imunologi',
                'kategori' => 'mcu',
                'order' => 4,
            ],
            [
                'nama_penyakit' => 'Penyakit Jantung',
                'kategori' => 'mcu',
                'order' => 5,
            ],
            [
                'nama_penyakit' => 'PPOK',
                'kategori' => 'mcu',
                'order' => 6,
            ],
            [
                'nama_penyakit' => 'Kanker',
                'kategori' => 'mcu',
                'order' => 7,
            ],
            [
                'nama_penyakit' => 'Penyakit Kronis Lainnya',
                'kategori' => 'mcu',
                'order' => 8,
            ],
            [
                'nama_penyakit' => 'Hamil',
                'kategori' => 'keadaan',
                'order' => 1,
            ],
            [
                'nama_penyakit' => 'Menyusui',
                'kategori' => 'keadaan',
                'order' => 2,
            ],
            [
                'nama_penyakit' => 'Vaksinasi Pertama',
                'kategori' => 'vaksin',
                'order' => 1,
            ],
            [
                'nama_penyakit' => 'Vaksinasi Kedua',
                'kategori' => 'vaksin',
                'order' => 2,
            ],
            [
                'nama_penyakit' => 'Vaksinasi Ketiga',
                'kategori' => 'vaksin',
                'order' => 3,
            ],
            [
                'nama_penyakit' => 'Vaksinasi Keempat',
                'kategori' => 'vaksin',
                'order' => 4,
            ],
        ];

        foreach ($data as $key => $value) {
            MasterMcu::updateOrCreate([
                'nama_penyakit' => $value['nama_penyakit'],
                'kategori' => $value['kategori'],
            ], [
                'order' => $value['order'],
            ]);
        }
    }
}
