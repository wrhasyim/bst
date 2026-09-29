<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/AkademikController.php';
require_once __DIR__ . '/../app/Core/Database.php';

class AkademikControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure session is started and mock user is logged in as admin
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_SESSION = [];
    }

    public function testKenaikan()
    {
        // 1. Mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'nama_kelas' => 'X MIPA 1']
            ]);

        // 2. Mock PDO
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->expects($this->once())
            ->method('query')
            ->with("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")
            ->willReturn($mockStatement);

        // 3. Inject mock PDO into Database singleton using Reflection without calling Database::getInstance()
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);

        // Create an instance without calling the constructor
        $dbInstance = $reflection->newInstanceWithoutConstructor();

        $connProperty = $reflection->getProperty('conn');
        $connProperty->setAccessible(true);
        $connProperty->setValue($dbInstance, $mockPdo);

        // Set the singleton instance
        $instanceProperty->setValue(null, $dbInstance);

        // 4. Instantiate Controller
        $controller = new AkademikController();

        // 5. Call kenaikan() and capture output
        ob_start();
        $controller->kenaikan();
        $output = ob_get_clean();

        // 6. Assertions
        $this->assertStringContainsString('Kenaikan Kelas Massal', $output);
        // The view would render X MIPA 1 if it echoes out the class name,
        // but even just checking the title is good enough to know it rendered.
        // Let's also assert it contains 'X MIPA 1' if the view outputs it.
        $this->assertStringContainsString('X MIPA 1', $output);
    }
}
