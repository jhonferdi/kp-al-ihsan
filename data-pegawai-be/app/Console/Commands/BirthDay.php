<?php

namespace App\Console\Commands;

use App\Models\BirthDay as ModelsBirthDay;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BirthDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Hari H
        $next_days = now();
        $pegs = Pegawai::whereMonth('peg_lahir_tanggal', $next_days->month)->whereDay('peg_lahir_tanggal',$next_days->day)->get();
        foreach ($pegs as $key => $peg) {
            $c = 'Hari ini '. $peg->peg_nama_lengkap . ' berulang tahun yang ke ' . Carbon::parse($peg->peg_lahir_tanggal)->age +1;
            $bd = new ModelsBirthDay();
            $bd->message = $c;
            $bd->peg_id = $peg->peg_id;
            $bd->hari = 1;
            $bd->save();
        }

        //H-3
        $days3 = now()->addDays(3);
        $pegs = Pegawai::whereMonth('peg_lahir_tanggal', $days3->month)->whereDay('peg_lahir_tanggal',$days3->day)->get();
        foreach ($pegs as $key => $peg) {
            $c = '3 Hari Lagi '. $peg->peg_nama_lengkap . ' berulang tahun yang ke ' . Carbon::parse($peg->peg_lahir_tanggal)->age +1;
            $bd = new ModelsBirthDay();
            $bd->message = $c;
            $bd->peg_id = $peg->peg_id;
            $bd->hari = 3;
            $bd->save();
        }
                

    }
}
