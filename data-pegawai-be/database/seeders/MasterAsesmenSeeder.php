<?php

namespace Database\Seeders;

use App\Models\MasterAsesmen;
use Illuminate\Database\Seeder;

class MasterAsesmenSeeder extends Seeder
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
                'jenis_asesmen_kompetensi' => 'PK 1',
                'order' => 1,
            ],
            [
                'jenis_asesmen_kompetensi' => 'PK 2',
                'order' => 2,
            ],
            [
                'jenis_asesmen_kompetensi' => 'PK 3',
                'order' => 3,
            ],
            [
                'jenis_asesmen_kompetensi' => 'PK 4',
                'order' => 4,
            ],
            [
                'jenis_asesmen_kompetensi' => 'PK 5',
                'order' => 5,
            ],
        ];

        foreach ($data as $key => $value) {
            MasterAsesmen::updateOrCreate([
                'jenis_asesmen_kompetensi' => $value['jenis_asesmen_kompetensi'],
            ], [
                'order' => $value['order'],
            ]);
        }
    }
}
