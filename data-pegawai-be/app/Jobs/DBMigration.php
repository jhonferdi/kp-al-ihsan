<?php

namespace App\Jobs;

use DB;
use Throwable;
use Exception;
use App\Helpers\LaravelBatch\BatchFacade as LaravelBatchBatchFacade;
use App\Models\JobExecution;
use App\Models\JobExecutionLog;

class DBMigration
{
    private $job;
    private $job_execution;

    public function __construct($job_execution)
    {
        ini_set('memory_limit', '20480M');
        set_time_limit(0);
        $this->job = $job_execution->job;
        $this->job_execution = $job_execution;
        $this->extractor = $this->job->source_connection->getExtractor();
        $this->loader = $this->job->destination_connection->getLoader();
    }

    public function run()
    {
        $job = $this->job;
        try {
            JobExecutionLog::makeLog($this->job_execution, "info", "Running job {$this->job->name}");
            $this->job_execution->updateProgress(4);
            $that = $this;
            $processRows = function($rows, $size, $offset) use ($that) { return $that->processRows($rows, $size, $offset); };
            $this->extractor->chunkData($job->query,$job->chunk_count, $processRows);
            JobExecutionLog::makeLog($this->job_execution, "success", "Sync completed for {$this->job->name}");
            $this->job_execution->markDone();
        } catch (Throwable $e) {
            $this->job_execution->markError();
            JobExecutionLog::makeLog($this->job_execution, "error", $e->getMessage());
        }
    }

    public function processRows($rows, $size, $offset)
    {
        $total_data = count($rows);
        JobExecutionLog::makeLog($this->job_execution, "info", "Fetched ".($total_data + $offset)." data");
        $inserts = [];
        foreach ($rows as $key => $row) {
            $inserts[] = $this->mapData($row);
        }
        if ($this->job->use_upsert) {
            $this->loader->doUpsert($this->job->destination_table, $inserts, $this->job->destination_keys);
        } else {
            $this->loader->doInsert($this->job->destination_table, $inserts);
        }
        JobExecutionLog::makeLog($this->job_execution, "info", "Inserted ".($total_data + $offset)." data");
        $n = min($offset / $size + 5, 99);
        $this->job_execution->updateProgress($n);
        return true;
    }

    public function mapData($row)
    {
        $res = [];
        foreach ($this->job->mappings as $map) {
            $save_to = $map['variable'];
            $jenis = $map['type'];
            switch ($jenis) {
                case 'map':
                    $res[$save_to] = $row->{$map['column']};
                    break;
                case 'const':
                    $res[$save_to] = eval("return ".$map['value'].";");;
                    break;
                case 'function':
                    $func = eval("return ".$map['function'].";");
                    $res[$save_to] = $func($row);
                    break;
            }
        }
        return $res;
    }

}
