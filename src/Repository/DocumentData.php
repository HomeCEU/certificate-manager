<?php


namespace HomeCEU\Repository;


class DocumentData
{
    public $key;
    public $type;
    public $data;

    public function __construct(string $type, string $key, array $data)
    {
        $this->key = $key;
        $this->data = $data;
        $this->type = $type;
    }

    public static function createFromJson(string $json): self
    {
        $data = json_decode($json);
        return new self($data->type, $data->key, (array) $data->data);
    }
}