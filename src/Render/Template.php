<?php declare(strict_types=1);


namespace HomeCEU\Render;


class Template
{
    public $id;
    public $name;
    public $body;
    public $constant;
    public $createdOn;
    public $updatedOn;

    public function __construct(
        string $id,
        string $constant,
        string $name,
        string $body,
        \DateTime $createdAt,
        \DateTime $updatedOn = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->body = $body;
        $this->constant = $constant;
        $this->createdOn = $createdAt;
        $this->updatedOn = $updatedOn;
    }
}
