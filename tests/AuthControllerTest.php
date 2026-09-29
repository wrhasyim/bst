<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // Mock Database singleton to prevent actual DB connections
        $pdoMock = $this->createMock(PDO::class);
        $dbMock = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dbMock->method('getConnection')->willReturn($pdoMock);

        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, $dbMock);

        // Start output buffering
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();

        // Reset Database singleton
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, null);
    }

    public function testLoginGetRequestRendersView()
    {
        $controller = new AuthController();

        // We shouldn't need any complex DB setup for a GET request since
        // AuthController's constructor only initializes the User model, which gets our mock PDO.

        // We can capture the output of the view
        $controller->login();
        $output = ob_get_clean();

        // Ensure that output buffering is restarted for tearDown
        ob_start();

        $this->assertStringContainsString('<title>Login - BST</title>', $output);
        $this->assertStringContainsString('Login', $output);
    }
}
