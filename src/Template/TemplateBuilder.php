<?php declare(strict_types=1);


namespace HomeCEU\Template;


class TemplateBuilder
{
    private $id;
    private $name;
    private $constant;
    private $body;

    public static function create(): self
    {
        return new self;
    }

    public function withId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withConstant(string $constant): self
    {
        $this->constant = $constant;
        return $this;
    }

    public function withBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function build(): Template
    {
        $createdAt = new \DateTime();
        $updatedOn = new \DateTime();

        return new Template(
            $this->id,
            $this->constant,
            $this->name,
            $this->body,
            $createdAt,
            $updatedOn
        );
    }
}
