<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPendidikan;

class MasterPendidikanSeeder extends Seeder
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
                'tingkat_pendidikan' => 'Sekolah Dasar',
                'order' => 1,
            ],
            [
                'tingkat_pendidikan' => 'Sekolah Menengah Pertama',
                'order' => 2,
            ],
            [
                'tingkat_pendidikan' => 'Sekolah Menengah Atas',
                'order' => 3,
            ],
            [
                'tingkat_pendidikan' => 'Diploma 1',
                'order' => 4,
            ],
            [
                'tingkat_pendidikan' => 'Diploma 3',
                'order' => 5,
            ],
            [
                'tingkat_pendidikan' => 'Sarjana 1 / Diploma 4',
                'order' => 6,
            ],
            [
                'tingkat_pendidikan' => 'Sarjana 2',
                'order' => 7,
            ],
            [
                'tingkat_pendidikan' => 'Sarjana 3',
                'order' => 8,
            ],
        ];

        foreach ($data as $key => $value) {
            MasterPendidikan::updateOrCreate([
                'tingkat_pendidikan' => $value['tingkat_pendidikan'],
            ], [
                'order' => $value['order'],
            ]);
        }
    }
}
