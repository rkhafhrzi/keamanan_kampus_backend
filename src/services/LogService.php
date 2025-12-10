<?php


require_once dirname(__DIR__) . '/models/LogAktivitas.php';

class LogService
{
    private $log;

    public function __construct()
    {
        $this->log = new LogAktivitas();
    }

    public function catat(array $data)
    {
        return $this->log->catat($data);
    }

    public function semua()
    {
        return $this->log->semua();
    }

    public function create(array $data)
    {
        return $this->log->catat($data);
    }
}
