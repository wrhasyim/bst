<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/ProfilController.php';
require_once __DIR__ . '/../app/Core/Database.php';

class ProfilControllerTest extends TestCase {
    protected function setUp(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    public function testIndex() {
        // Prepare session for the controller constructor and views
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin'; // Used in views/layouts/admin.php

        // Mock Database, PDO, and PDOStatement
        $dbMock = $this->createMock(Database::class);
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Configure PDOStatement mock
        $mockProfileData = [
            'id' => 1,
            'nama' => 'John Doe',
            'username' => 'johndoe',
            'nama_kelas' => 'X RPL',
            'role' => 'admin' // Fixed warning by adding role to profile output
        ];
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn($mockProfileData);

        // Configure PDO mock
        $pdoMock->method('prepare')->willReturn($stmtMock);

        // Configure Database mock
        $dbMock->method('getConnection')->willReturn($pdoMock);

        // Inject the mocked Database instance using Reflection
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, $dbMock);

        // Instantiate controller
        $controller = new ProfilController();

        // Capture output of the index method
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        // Assertions
        $this->assertStringContainsString('PROFIL<span class="text-emerald-500">SAYA</span>', $output, "The page title should be visible.");
        $this->assertStringContainsString('JOHN DOE', strtoupper($output), "The user's name should be displayed in the output.");
        $this->assertStringContainsString('@johndoe', $output, "The user's username should be displayed in the output.");

        // Reset the singleton instance for other tests
        $instanceProperty->setValue(null, null);
    }
}
