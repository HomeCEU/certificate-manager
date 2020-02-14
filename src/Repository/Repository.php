<?php


namespace HomeCEU\Repository;


class Repository
{
    protected $conn;

    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }
}