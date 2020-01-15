<?php declare(strict_types=1);


namespace HomeCEU\Template;


use Ramsey\Uuid\Uuid;

class Repository
{
    private $conn;

    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(string $name, string $template): Template
    {
        return new Template($this->generateId(), $name, $template);
    }

    public function save(Template $template): void
    {
        $sql = <<<SQL
            INSERT INTO template 
                (template_id, constant, name, body)
            VALUES
                (:templateId, :constant, :name, :body);            
            SQL;
        $st = $this->conn->prepare($sql);

        $st->bindParam('templateId', $template->getId());
        $st->bindParam('constant', $template->getName());
        $st->bindParam('name', $template->getName());
        $st->bindParam('body', $template->getRawTemplate());

        $st->execute();
    }

    public function findById(string $id): ?Template
    {
        $st = $this->conn->prepare('SELECT * FROM template WHERE template_id = ?');
        $st->execute([$id]);

        $result = $st->fetch(\PDO::FETCH_ASSOC);

        if ($result !== false) {
            return new Template($result['template_id'], $result['name'], $result['body']);
        }
        return null;
    }

    protected function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
