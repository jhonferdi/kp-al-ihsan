<?php

function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

function getUserAgent() {
    $user_agent = request()->header('User-Agent');
    if (!$user_agent) {
        $user_agent = 'Unknown';
    }
    return $user_agent;
}

function makeDynamicModel($table, $connection) {
    return new class($table, $connection) extends \Illuminate\Database\Eloquent\Model {
        protected $connection = 'test';
        protected $table = 'test';
        public $timestamps = false;

        public function __construct($table, $connection)
        {
            parent::__construct([]);
            $this->table = $table;
            $this->connection = $connection;
        }
    };
}
function makeLog($params)
{
    $user_id = auth()->user() ? auth()->user()->id : null;

    Action::create(array_merge([
        'user_id' => $user_id,
    ], $params));
}
function cctor($obj) {
    return clone $obj;
}
function getFullDate($time = 0) {
    if ($time == "") return $time;
    if ($time && $time != "0000-00-00") {
        $time = strtotime($time);
    } else {
        return "-";
    }
    return date("j",$time)." ".listBulan()[date("n",$time)]." ".date("Y",$time);
}
function getFullDateTime($time = 0) {
    if ($time == "") return $time;
    if ($time && $time != "0000-00-00 00:00:00") {
        $time = strtotime($time);
    } else {
        return "-";
    }
    return date("j",$time)." ".listBulan()[date("n",$time)]." ".date("Y H:i:s",$time);
}
function getFullDateDayTime($time = 0) {
    if ($time == "") return $time;
    if ($time && $time != "0000-00-00 00:00:00") {
        $time = strtotime($time);
    } else {
        return "-";
    }
    return listHari()[date("N",$time)].", ".date("j",$time)." ".listBulan()[date("n",$time)]." ".date("Y H:i:s",$time);
}
function listBulan() {
    return [""=>"-",1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
}
function listHari() {
    return [""=>"-",1=>'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
}
function listBulanShort() {
    return [""=>"-",1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
}
