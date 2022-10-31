<?php

namespace App\Jobs\Loader;

use DB;
use Config;
use Exception;
use Batch;
use Illuminate\Support\Str;

class MySQLLoader
{
    public function __construct($connection)
    {
        if (!$connection) throw new Exception("Connection is required", 1);
        if ($connection->driver != 'mysql') throw new Exception("Loader type mismatch", 1);
        
        $connection->connectDB();
        $this->connection = $connection;
    }

    public function doInsert($table, $data)
    {
        DB::connection($this->connection->slug)->table($table)->insert($data);
    }

    public function doUpsert($table, $data, $keys)
    {
        $instance = makeDynamicModel($table, $this->connection->slug);
        $instance->upsert($data, $keys);
    }

    public function doDelete($table, $data, $keys = [])
    {
        foreach ($data as $row) {
            if (count($keys)) {
                DB::connection($this->connection->slug)->table($table)->where(array_filter(
                    $row,
                    function ($key) use ($keys) {
                        return in_array($key, $keys);
                    },
                    ARRAY_FILTER_USE_KEY
                ))->delete();
            } else {
                DB::connection($this->connection->slug)->table($table)->where($row)->delete();
            }
        }
    }

}
