<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Setoran.php';

class SetoranTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $setoran;

    public function setUp(): void
    {
        parent::setUp();

        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Mock Database singleton
        $mockDb = new class($this->pdoMock) {
            private $conn;
            public function __construct($conn) {
                $this->conn = $conn;
            }
            public function getConnection() {
                return $this->conn;
            }
        };

        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, $mockDb);

        $this->setoran = new Setoran();
    }

    public function tearDown(): void
    {
        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);

        parent::tearDown();
    }

    public function testGetSetoranSiswaLimitDefaultValue()
    {
        $expectedResult = [
            ['id' => 1, 'nama_siswa' => 'Test User', 'nama_kelas' => '10A', 'nama_sampah' => 'Plastik']
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('LIMIT :limit'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindValue')
            ->with(':limit', 50, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult);

        $result = $this->setoran->getSetoranSiswa();

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetSetoranSiswaLimitCustomValue()
    {
        $expectedResult = [
            ['id' => 1, 'nama_siswa' => 'Test User', 'nama_kelas' => '10A', 'nama_sampah' => 'Plastik']
        ];
        $customLimit = 10;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('LIMIT :limit'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindValue')
            ->with(':limit', $customLimit, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult);

        $result = $this->setoran->getSetoranSiswa($customLimit);

        $this->assertEquals($expectedResult, $result);
    }
}
