<?php


namespace HomeCEU\Repository;


class DocumentDataRepository extends Repository
{
    public function save(DocumentData $data): void
    {
        $sql = <<<SQL
                REPLACE INTO document_data
                    (data_key, document_type, data) 
                VALUES 
                    (:data_key, :document_type, :data)
                SQL;
        $st = $this->conn->prepare($sql);

        $json = json_encode($data->data);

        $st->bindParam('data_key', $data->key);
        $st->bindParam('document_type', $data->type);
        $st->bindParam('data', $json);

        $st->execute();
    }

    public function find(string $typeConstant, string $key): ?DocumentData
    {
        $st = $this->conn->prepare('SELECT * FROM document_data WHERE document_type = :type AND data_key = :key');
        $st->execute(['type' => $typeConstant, 'key' => $key]);

        $result = $st->fetch(\PDO::FETCH_ASSOC);

        if ($result !== false) {
            $data = json_decode($result['data'], true);
            return new DocumentData($result['document_type'], $result['data_key'], $data);
        }
        return null;
    }

    public function remove(string $typeConstant, string $key): void
    {
        $st = $this->conn->prepare('DELETE FROM document_data WHERE document_type = :type AND data_key = :key');
        $st->execute(['type' => $typeConstant, 'key' => $key]);
    }
}