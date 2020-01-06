<?php declare(strict_types=1);


namespace HomeCEU\Template;


use Ramsey\Uuid\Uuid;

class Repository
{
    private $store = [];

    public function create(string $name, string $template): Template
    {
        return new Template(
            Uuid::uuid4()->toString(),
            $name,
            $template
        );
    }

    public function save(Template $template): void
    {
        $this->store[$template->getId()] = $template;
    }

    public function findById(string $id): ?Template
    {
        return $this->store[$id] ?? null;
    }
}
