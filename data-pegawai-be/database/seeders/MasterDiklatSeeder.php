<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterDiklat;

class MasterDiklatSeeder extends Seeder
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
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'BHD / BLS',
                'order' => 1,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'K3 / DAMKAR',
                'order' => 2,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'KOMUNIKASI EFEKTIF',
                'order' => 3,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'PPI',
                'order' => 4,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'PATIENT SAFETY',
                'order' => 5,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'MPKP / SP2KP',
                'order' => 6,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Dasar',
                'jenis_pelatihan' => 'LAIN-LAIN',
                'order' => 7,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'HIPERKES',
                'order' => 1,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PERAWATAN LUKA',
                'order' => 2,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PPI DASAR',
                'order' => 3,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PPGD',
                'order' => 4,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'ENIL / ENBL',
                'order' => 5,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'KEMOTERAPI *)SPM',
                'order' => 6,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PALIATIF CARE',
                'order' => 7,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'BTCLS',
                'order' => 8,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'ACLS',
                'order' => 9,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'TRIAGE',
                'order' => 10,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'CODE BLUE',
                'order' => 11,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'EKG DASAR',
                'order' => 12,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'INTENSIF DASAR',
                'order' => 13,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'CARDIOLOGI DASAR *)WAJIB DI ICCU',
                'order' => 14,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'CARDIOLOGI LANJUTAN',
                'order' => 15,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'ICCU',
                'order' => 16,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'NICU',
                'order' => 17,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'MANAJEMEN INTENSIF',
                'order' => 18,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'KAMAR BEDAH',
                'order' => 19,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'KAMAR BEDAH KHUSUS',
                'order' => 20,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'HD',
                'order' => 21,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'CAPD',
                'order' => 22,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'NEONATUS DASAR',
                'order' => 23,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'RESUS NEONATAL',
                'order' => 24,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'KEGAWAT DARURATAN PD IBU DAN NEONATUS',
                'order' => 25,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'MANAJEMEN LAKTASI',
                'order' => 26,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'CTU',
                'order' => 27,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'APN',
                'order' => 28,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PPGD ON',
                'order' => 29,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PONEK',
                'order' => 30,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'MANAJEMEN BANGSAL',
                'order' => 31,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PENGADAAN BARANG & JASA',
                'order' => 32,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'KOMITE KEPERAWATAN',
                'order' => 33,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'PRESEFTOR',
                'order' => 34,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'ASESOR',
                'order' => 35,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'RESERTIFIKASI',
                'order' => 36,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Keahlian',
                'jenis_pelatihan' => 'LAIN-LAIN',
                'order' => 37,
            ],
            [
                'kategori_pelatihan' => 'Pelatihan Lainnya',
                'jenis_pelatihan' => 'LAIN-LAIN',
                'order' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            MasterDiklat::updateOrCreate([
                'kategori_pelatihan' => $value['kategori_pelatihan'],
                'jenis_pelatihan' => $value['jenis_pelatihan'],
            ], [
                'order' => $value['order'],
            ]);
        }
    }
}
