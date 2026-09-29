<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Models/User.php';
require_once __DIR__ . '/../../app/Core/Database.php';

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    protected function tearDown(): void
    {
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    public function testGetAllReturnsListOfUsers()
    {
        // Mock PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);

        $expectedData = [
            ['id' => 1, 'nama' => 'Admin User', 'role' => 'admin', 'nama_kelas' => null],
            ['id' => 2, 'nama' => 'Student User', 'role' => 'siswa', 'nama_kelas' => '10A'],
        ];

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedData);

        // Mock PDO
        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->stringContains('SELECT u.*, k.nama_kelas'))
                ->willReturn($stmtMock);

        // Mock Database
        $dbMock = $this->createMock(Database::class);
        $dbMock->expects($this->once())
               ->method('getConnection')
               ->willReturn($pdoMock);

        // Inject Database instance
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, $dbMock);

        // Run code under test
        $userModel = new User();
        $result = $userModel->getAll();

        // Assert result
        $this->assertIsArray($result);
        $this->assertEquals($expectedData, $result);
    }
}
