<?php

namespace App\Jobs\Extractor;

use DB;
use Config;
use Exception;
use Illuminate\Support\Str;

class PostgreSQLExtractor
{
    public static function testConnection($params)
    {
        $slug = Str::random(10);
        try {
            Config::set('database.connections.' . $slug, array_merge([
                'driver' => 'pgsql',
            ],$params));
            DB::reconnect($slug);
            DB::connection($slug)->select("SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = '".$params['schema']."'
            ORDER BY table_name");
        } catch (Exception $th) {
            return [false, $th->getMessage()];
        }
        return [true,''];
    }

    public function __construct($connection)
    {
        if (!$connection) throw new Exception("Connection is required", 1);
        if ($connection->driver != 'pgsql') throw new Exception("Extractor type mismatch", 1);
        
        $connection->connectDB();
        $this->connection = $connection;
    }

    public function getMetadata($table)
    {
        $data = DB::connection($this->connection->slug)->select("SELECT *
        FROM information_schema.columns
       WHERE table_schema = '{$this->connection->schema}'
         AND table_name   = '$table'");
        $res = [];
        foreach ($data as $row) {
            $res[] = [
                'type' => $row->data_type,
                'column' => $row->column_name,
                'nullable' => $row->is_nullable,
            ];
        };
        return $res;
    }

    public function getMetadataQuery($query)
    {
        $data = DB::connection($this->connection->slug)->select($query . " limit 1");
        $res = [];
        foreach ($data as $row) {
            foreach ($row as $col => $val) {
                $type = gettype($val);
                $res[] = [
                    'type' => $type != 'NULL' ? $type : 'unknown',
                    'column' => $col,
                ];
            }
        };
        return $res;
    }

    public function chunkData($query, $size, $callback)
    {
        $offset = 0;
        do {
            $result_data = DB::connection($this->connection->slug)->select($query . " LIMIT $size OFFSET $offset");
            if (count($result_data) > 0) {
                $continue = $callback($result_data, $size, $offset);
                if (!$continue) break;
            }
            $offset += $size;
        } while (count($result_data) > 0);
    }
}
