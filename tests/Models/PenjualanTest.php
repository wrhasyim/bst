<?php
use PHPUnit\Framework\TestCase;

// Include config.php first so our dummy definitions don't trigger warnings if it gets included later
require_once __DIR__ . '/../../config.php';

// Dummy config to prevent errors when Database is loaded, only if not defined already
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'test');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');


require_once __DIR__ . '/../../app/Models/penjualan.php';
require_once __DIR__ . '/../../app/Core/Database.php';

class PenjualanTest extends TestCase
{
    private $dbMock;
    private $pdoMock;
    private $penjualan;

    protected function setUp(): void
    {
        // Mock PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Mock Database singleton
        $this->dbMock = $this->createMock(Database::class);
        $this->dbMock->method('getConnection')->willReturn($this->pdoMock);

        // Inject mock into Database instance using Reflection
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, $this->dbMock);

        // Initialize model
        $this->penjualan = new Penjualan();
    }

    public function testCreateSuccess()
    {
        $data = [
            'kategori_id' => 1,
            'total_berat' => 10,
            'harga_per_kg' => 5000,
            'total_pendapatan' => 50000,
            'keterangan' => 'Test'
        ];

        // Mocks for statements
        $stmtInsertMock = $this->createMock(PDOStatement::class);
        $stmtInsertMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $stmtUpdateMock = $this->createMock(PDOStatement::class);
        $stmtUpdateMock->expects($this->once())
            ->method('execute')
            ->with(['kategori_id' => $data['kategori_id']])
            ->willReturn(true);

        // Expect transaction methods
        $this->pdoMock->expects($this->once())->method('beginTransaction');
        $this->pdoMock->expects($this->once())->method('commit');
        $this->pdoMock->expects($this->never())->method('rollBack');

        // Configure prepare to return the correct statement mock based on SQL
        $this->pdoMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(function ($sql) use ($stmtInsertMock, $stmtUpdateMock) {
                if (strpos($sql, 'INSERT INTO penjualan') !== false) {
                    return $stmtInsertMock;
                }
                if (strpos($sql, 'UPDATE setoran') !== false) {
                    return $stmtUpdateMock;
                }
                return null;
            });

        $result = $this->penjualan->create($data);

        $this->assertTrue($result);
    }

    public function testCreateExceptionRollsBack()
    {
        $data = [
            'kategori_id' => 1,
            'total_berat' => 10,
            'harga_per_kg' => 5000,
            'total_pendapatan' => 50000,
            'keterangan' => 'Test'
        ];

        $this->pdoMock->expects($this->once())->method('beginTransaction');

        // Throw exception on prepare
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willThrowException(new Exception("DB Error"));

        $this->pdoMock->expects($this->never())->method('commit');
        $this->pdoMock->expects($this->once())->method('rollBack');

        $result = $this->penjualan->create($data);

        $this->assertFalse($result);
    }
}
