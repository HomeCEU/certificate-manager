<?php


namespace HomeCEU\Repository;


use HomeCEU\Document\DocumentType;

class DocumentTypeRepository extends Repository
{
    public function save(DocumentType $documentType): void
    {
        $sql = <<<SQL
                INSERT INTO document_type
                    (constant, name, description)
                VALUES 
                    (:constant, :name, :description)
                SQL;
        $st = $this->conn->prepare($sql);

        $st->bindParam('constant', $documentType->constant);
        $st->bindParam('name', $documentType->name);
        $st->bindParam('description', $documentType->description);

        $st->execute();
    }

    public function findByConstant(string $constant): ?DocumentType
    {
        $st = $this->conn->prepare('SELECT * FROM document_type WHERE constant = ?');
        $st->execute([$constant]);

        $result = $st->fetch(\PDO::FETCH_ASSOC);

        if ($result !== false) {
            return new DocumentType($result['constant'], $result['name'], $result['description']);
        }
        return null;
    }

    public function remove(string $constant): void
    {
        $st = $this->conn->prepare('DELETE FROM document_type WHERE constant = ?');
        $st->execute([$constant]);
    }
}