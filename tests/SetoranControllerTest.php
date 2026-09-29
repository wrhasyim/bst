<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Controllers/SetoranController.php';

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SetoranControllerTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $countStmtMock;

    public function setUp(): void
    {
        parent::setUp();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['role'] = 'admin';
        $_SESSION['user_id'] = 1;
        $_SESSION['nama'] = 'Test Admin';

        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->countStmtMock = $this->createMock(PDOStatement::class);

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
    }

    public function tearDown(): void
    {
        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $_GET = [];
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }
        parent::tearDown();
    }

    public function testSiswaMethodPaginationDefaultPage()
    {
        $this->countStmtMock->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(25);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with($this->stringContains('SELECT COUNT(*)'))
            ->willReturn($this->countStmtMock);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('LIMIT :limit OFFSET :offset'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('bindValue')
            ->willReturnCallback(function($param, $value, $type) {
                if ($param === ':limit') {
                    $this->assertEquals(10, $value);
                } elseif ($param === ':offset') {
                    $this->assertEquals(0, $value);
                }
                $this->assertEquals(PDO::PARAM_INT, $type);
                return true;
            });

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $controller = new SetoranController();

        ob_start();
        $controller->siswa();
        $output = ob_get_clean();

        // Assert that the page is properly rendered
        $this->assertStringContainsString('Riwayat Tabungan', $output);
    }

    public function testSiswaMethodPaginationCustomPage()
    {
        $_GET['page'] = 3;

        $this->countStmtMock->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(25);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with($this->stringContains('SELECT COUNT(*)'))
            ->willReturn($this->countStmtMock);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('LIMIT :limit OFFSET :offset'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('bindValue')
            ->willReturnCallback(function($param, $value, $type) {
                if ($param === ':limit') {
                    $this->assertEquals(10, $value);
                } elseif ($param === ':offset') {
                    $this->assertEquals(20, $value);
                }
                $this->assertEquals(PDO::PARAM_INT, $type);
                return true;
            });

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $controller = new SetoranController();

        ob_start();
        $controller->siswa();
        $output = ob_get_clean();

        // Assert that the page is properly rendered
        $this->assertStringContainsString('Riwayat Tabungan', $output);
    }
}
