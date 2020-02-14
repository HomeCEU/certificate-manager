<?php declare(strict_types=1);


namespace HomeCEU\Tests\Repository;


use HomeCEU\Document\DocumentType;
use HomeCEU\Repository\DocumentData;
use HomeCEU\Repository\DocumentDataRepository;
use HomeCEU\Repository\DocumentTypeRepository;

class DocumentDataStorageTest extends RepositoryTestCase
{
    const DOC_TYPE_CONSTANT = 'template';

    private $repo;
    private $documentDataArray = [];
    private $documentJson;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDefaultDocType();

        $this->repo = new DocumentDataRepository($this->conn);

        $this->documentDataArray = [
            'type' => self::DOC_TYPE_CONSTANT,
            'key' => uniqid('key_'),
            'data' => ['test' => 'some data']
        ];
        $this->documentJson = json_encode($this->documentDataArray);
    }

    public function testCreateFromJson(): void
    {
        $documentData = DocumentData::createFromJson($this->documentJson);

        $this->assertEquals($documentData->key, $this->documentDataArray['key']);
        $this->assertEquals($documentData->data, $this->documentDataArray['data']);
    }

    public function testSave(): void
    {
        $documentData = DocumentData::createFromJson($this->documentJson);
        $this->repo->save($documentData);

        $foundData = $this->repo->find(self::DOC_TYPE_CONSTANT, $documentData->key);
        $this->assertInstanceOf(DocumentData::class, $foundData);
    }

    public function testRemove(): void
    {
        $documentData = DocumentData::createFromJson($this->documentJson);
        $this->repo->save($documentData);
        $this->repo->remove(self::DOC_TYPE_CONSTANT, $documentData->key);

        $foundData = $this->repo->find(self::DOC_TYPE_CONSTANT, $documentData->key);
        $this->assertNull($foundData);
    }

    protected function createDefaultDocType(): void
    {
        $docType = new DocumentType(self::DOC_TYPE_CONSTANT, 'Template', 'Description');
        $docTypeRepo = new DocumentTypeRepository($this->conn);
        $docTypeRepo->save($docType);
    }
}
