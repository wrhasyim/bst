<?php
// tests/Models/PengaturanTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Models/Pengaturan.php';
require_once __DIR__ . '/../../app/Core/Database.php';

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[CoversClass(Pengaturan::class)]
#[AllowMockObjectsWithoutExpectations]
class PengaturanTest extends TestCase {

    private $dbMock;
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void {
        parent::setUp();

        // Mock PDO statement
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Mock PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Mock Database singleton
        $this->dbMock = $this->createMock(Database::class);
        $this->dbMock->method('getConnection')->willReturn($this->pdoMock);

        // Inject mock Database into Database::$instance via Reflection
        $reflection = new ReflectionClass(Database::class);
        $instanceProp = $reflection->getProperty('instance');
        $instanceProp->setAccessible(true);
        $instanceProp->setValue(null, $this->dbMock);
    }

    protected function tearDown(): void {
        // Reset Database singleton instance
        $reflection = new ReflectionClass(Database::class);
        $instanceProp = $reflection->getProperty('instance');
        $instanceProp->setAccessible(true);
        $instanceProp->setValue(null, null);

        parent::tearDown();
    }

    public function testUpdateSettingsSuccess() {
        // Setup data
        $data = [
            'nama_sekolah' => 'SMAN 1',
            'kepala_sekolah' => 'Budi'
        ];

        // Prepare will return statement
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with("UPDATE pengaturan SET nilai = :nilai WHERE kunci = :kunci")
            ->willReturn($this->stmtMock);

        // Begin transaction expected
        $this->pdoMock->expects($this->once())
            ->method('beginTransaction');

        // Execute expected to be called twice (once for each item)
        $this->stmtMock->expects($this->exactly(2))
            ->method('execute')
            ->willReturnCallback(function($args) {
                return true; // Simulate success
            });

        // Commit expected
        $this->pdoMock->expects($this->once())
            ->method('commit');

        // RollBack should NOT be called
        $this->pdoMock->expects($this->never())
            ->method('rollBack');

        $model = new Pengaturan();
        $result = $model->updateSettings($data);

        $this->assertTrue($result, 'updateSettings should return true on success');
    }

    public function testUpdateSettingsFailure() {
        // Setup data
        $data = [
            'nama_sekolah' => 'SMAN 1'
        ];

        // Prepare will return statement
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        // Begin transaction expected
        $this->pdoMock->expects($this->once())
            ->method('beginTransaction');

        // Execute expected to throw Exception
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willThrowException(new Exception("DB Error"));

        // Commit should NOT be called
        $this->pdoMock->expects($this->never())
            ->method('commit');

        // RollBack expected
        $this->pdoMock->expects($this->once())
            ->method('rollBack');

        $model = new Pengaturan();
        $result = $model->updateSettings($data);

        $this->assertFalse($result, 'updateSettings should return false on failure');
    }
}