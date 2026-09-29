<?php
namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use PDO;
use PDOStatement;
require_once __DIR__ . '/../../app/Core/Database.php';
require_once __DIR__ . '/../../app/Controllers/UserController.php';

use UserController;
use Database;

// Define BASE_URL if it's not defined, used by some controllers or views
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost');
}

class UserControllerTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();

        // Setup session for Security::requireRole()
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';

        // Mock PDOStatement for Users
        $stmtMockUsers = $this->createStub(PDOStatement::class);
        $stmtMockUsers->method('fetchAll')->willReturn([
            [
                'id' => 1,
                'nama' => 'Test User',
                'username' => 'testuser',
                'nama_kelas' => '10A',
                'kelas_id' => 1,
                'angkatan' => 2024,
                'role' => 'siswa',
                'is_active' => 1,
                'deleted_at' => null
            ]
        ]);

        // Mock PDOStatement for Kelas
        $stmtMockKelas = $this->createStub(PDOStatement::class);
        $stmtMockKelas->method('fetchAll')->willReturn([
            ['id' => 1, 'nama_kelas' => '10A']
        ]);

        // Mock PDO
        $pdoMock = $this->createStub(PDO::class);
        $pdoMock->method('query')->willReturnCallback(function($sql) use ($stmtMockUsers, $stmtMockKelas) {
            if (strpos($sql, 'FROM users') !== false) {
                return $stmtMockUsers;
            } elseif (strpos($sql, 'FROM kelas') !== false) {
                return $stmtMockKelas;
            }
            return $this->createMock(PDOStatement::class);
        });

        // Create Mock Database
        $dbMock = $this->createStub(Database::class);
        $dbMock->method('getConnection')->willReturn($pdoMock);

        // Inject Mock Database into Database Singleton
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, $dbMock);
    }

    protected function tearDown(): void {
        // Reset Database Singleton
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, null);

        parent::tearDown();
    }

    public function testIndexRendersData() {
        $controller = new UserController();

        // Capture output
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        // Verify that the view was rendered correctly
        $this->assertStringContainsString('Manajemen Data Pengguna', $output, "Output should contain the page title");
        $this->assertStringContainsString('Test User', $output, "Output should contain the user's name");
        $this->assertStringContainsString('testuser', $output, "Output should contain the username");
        $this->assertStringContainsString('10A', $output, "Output should contain the class name");
    }
}
