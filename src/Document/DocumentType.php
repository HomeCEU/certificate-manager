<?php


namespace HomeCEU\Document;


class DocumentType
{
    public $constant;
    public $name;
    public $description;

    public function __construct(string $constant, string $name, string $description = '')
    {
        $this->constant = $constant;
        $this->name = $name;
        $this->description = $description;
    }
}