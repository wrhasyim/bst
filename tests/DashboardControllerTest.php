<?php

use PHPUnit\Framework\TestCase;

// Make sure session is started to prevent PHP_SESSION_NONE issues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Load config.php to properly define BASE_URL and any other required constants
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';

class DashboardControllerTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;

    public function setUp(): void
    {
        parent::setUp();

        // Setup Session
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama'] = 'Admin Test';

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
    }

    public function tearDown(): void
    {
        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);

        unset($_SESSION['user_id']);
        unset($_SESSION['role']);
        unset($_SESSION['nama']);

        parent::tearDown();
    }

    public function testIndexAdminRole()
    {
        // Mock query method which returns a PDOStatement (for fetchColumn and fetchAll)
        $this->pdoMock->method('query')
            ->willReturn($this->stmtMock);

        $this->pdoMock->method('prepare')
            ->willReturn($this->stmtMock);

        // When fetchColumn is called, return dummy values
        $this->stmtMock->method('fetchColumn')
            ->willReturn('100'); // Dummy value

        $this->stmtMock->method('fetchAll')
            ->willReturn([]); // Empty leaderboard

        $this->stmtMock->method('execute')
            ->willReturn(true);

        $controller = new DashboardController();

        ob_start();
        $controller->index();
        $output = ob_get_clean();

        // Assert that the page rendered correctly with not empty output
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('BST', $output);
        $this->assertStringContainsString('DASHBOARD', $output);
    }
}
