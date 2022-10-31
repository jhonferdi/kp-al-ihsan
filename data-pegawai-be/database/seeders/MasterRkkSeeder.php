<?php

namespace Database\Seeders;

use App\Models\MasterRkk;
use Illuminate\Database\Seeder;

class MasterRkkSeeder extends Seeder
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
                'jenis_rkk_spkk_jk' => 'Pra-PK',
                'order' => 2,
            ],
            [
                'jenis_rkk_spkk_jk' => 'PK 1',
                'order' => 2,
            ],
            [
                'jenis_rkk_spkk_jk' => 'PK 2',
                'order' => 3,
            ],
            [
                'jenis_rkk_spkk_jk' => 'PK 3',
                'order' => 4,
            ],
            [
                'jenis_rkk_spkk_jk' => 'PK 4',
                'order' => 5,
            ],
            [
                'jenis_rkk_spkk_jk' => 'PK 5',
                'order' => 6,
            ],
        ];

        foreach ($data as $key => $value) {
            MasterRkk::updateOrCreate([
                'jenis_rkk_spkk_jk' => $value['jenis_rkk_spkk_jk'],
            ], [
                'order' => $value['order'],
            ]);
        }
    }
}
