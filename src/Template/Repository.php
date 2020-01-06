<?php declare(strict_types=1);


namespace HomeCEU\Template;


use Ramsey\Uuid\Uuid;

class Repository
{
    private $store = [];

    public function create(string $name, string $template): Template
    {
        return new Template($this->generateId(), $name, $template);
    }

    public function save(Template $template): void
    {
        $this->store[$template->getId()] = $template;
    }

    public function findById(string $id): ?Template
    {
        return $this->store[$id] ?? null;
    }

    protected function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
