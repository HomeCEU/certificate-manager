<?php


namespace HomeCEU\Tests\Repository;

use HomeCEU\Document\DocumentType;
use HomeCEU\Repository\DocumentTypeRepository;

class DocumentTypeStorageTest extends RepositoryTestCase
{
    private $repo;
    private $docType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new DocumentTypeRepository($this->conn);
        $this->docType = new DocumentType('a-constant', 'a name', 'a description of sorts');

    }

    public function testSave(): void
    {
        $this->repo->save($this->docType);

        $foundType = $this->repo->findByConstant($this->docType->constant);
        $this->assertDocumentTypesAreTheSame($foundType);
    }

    public function testRemove(): void
    {
        $docType = new DocumentType('a-constant', 'a name', 'a description of sorts');
        $this->repo->save($docType);
        $this->repo->remove($docType->constant);

        $this->assertNull($this->repo->findByConstant($docType->constant));
    }

    private function assertDocumentTypesAreTheSame(DocumentType $foundType)
    {
        $this->assertEquals($this->docType->name, $foundType->name);
        $this->assertEquals($this->docType->constant, $foundType->constant);
        $this->assertEquals($this->docType->description, $foundType->description);
    }
}