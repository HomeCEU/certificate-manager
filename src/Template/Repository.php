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

    public function create(string $constant, string $name, string $body): Template
    {
        return TemplateBuilder::create()
                              ->withId($this->generateTemplateId())
                              ->withConstant($constant)
                              ->withName($name)
                              ->withBody($body)
                              ->build();
    }

    public function save(Template $template): void
    {
        $sql = <<<SQL
            INSERT INTO template 
                (template_id, constant, name, body, created_on, updated_on)
            VALUES
                (:templateId, :constant, :name, :body, :createdOn, :updatedOn);
            SQL;
        $st  = $this->conn->prepare($sql);

        $st->bindParam('templateId', $template->id);
        $st->bindParam('constant', $template->constant);
        $st->bindParam('createdOn', $template->createdAt->format('Y-m-d H:i:s'));
        $st->bindParam('updatedOn', $template->updatedOn->format('Y-m-d H:i:s'));
        $st->bindParam('name', $template->name);
        $st->bindParam('body', $template->body);

        $st->execute();
    }

    public function findById(string $id): ?Template
    {
        $st = $this->conn->prepare('SELECT * FROM template WHERE template_id = ?');
        $st->execute([$id]);

        $result = $st->fetch(\PDO::FETCH_ASSOC);

        if ($result !== false) {
            return TemplateBuilder::create()
                                  ->withId($result['template_id'])
                                  ->withConstant($result['constant'])
                                  ->withName($result['name'])
                                  ->withBody($result['body'])
                                  ->withCreatedAt(new \DateTime($result['created_on']))
                                  ->withUpdatedOn(new \DateTime($result['updated_on']))
                                  ->build();
        }
        return null;
    }

    protected function generateTemplateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
