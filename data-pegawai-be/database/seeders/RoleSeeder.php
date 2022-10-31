<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        foreach ($this->items() as $item) {
            Role::create([
                'id' => $item['id'],
                'role_name' => $item['role_name']
            ]);
        }
    }

    public function items(): array
    {
        return [
            [
                "id" => 1,
                "role_name" => 'Admin Global'
            ],
            [
                "id" => 2,
                "role_name" => 'Admin per aplikasi'
            ],
            [
                "id" => 3,
                "role_name" => 'Admin unit kerja per aplikasi'
            ],
            [
                "id" => 4,
                "role_name" => 'Pegawai ASN'
            ],
            [
                "id" => 5,
                "role_name" => 'Pegawai Non ASN'
            ],
            [
                "id" => 6,
                "role_name" => 'Adpeg (administrator kepegawaian) per unit kerja'
            ],
            [
                "id" => 7,
                "role_name" => 'Pimpinan (kepala satker)'
            ],
        ];
    }
}
