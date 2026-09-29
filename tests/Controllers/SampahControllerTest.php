<?php

use PHPUnit\Framework\TestCase;

class SampahControllerTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();

        // Mock session parameters to bypass Security::requireRole(['admin'])
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['nama'] = 'Test Admin';
    }

    protected function tearDown(): void {
        session_unset();
        parent::tearDown();
    }

    public function testIndexMethodRetrievesCategoriesAndRendersView() {
        require_once __DIR__ . '/../../app/Controllers/SampahController.php';

        // Set up the Database mock
        $mockDb = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        // Mock data that includes the missing keys expected by the view
        $mockData = [
            [
                'id' => 1,
                'nama_sampah' => 'Kardus',
                'harga_dasar' => 1000,
                'harga_guru' => 1100,
                'harga_pengepul' => 1200,
                'satuan' => 'Kg',
                'konversi_kg' => 1
            ],
            [
                'id' => 2,
                'nama_sampah' => 'Plastik',
                'harga_dasar' => 2000,
                'harga_guru' => 2100,
                'harga_pengepul' => 2200,
                'satuan' => 'Kg',
                'konversi_kg' => 1
            ],
        ];

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockData);

        $mockDb->expects($this->once())
            ->method('query')
            ->with($this->stringContains('SELECT * FROM kategori_sampah'))
            ->willReturn($mockStatement);

        // Inject the mocked DB into the Singleton Database class using Reflection
        $reflectionClass = new ReflectionClass('Database');
        $instanceProperty = $reflectionClass->getProperty('instance');
        $instanceProperty->setAccessible(true);

        $mockDatabaseInstance = $this->getMockBuilder('Database')
            ->disableOriginalConstructor()
            ->getMock();

        $mockDatabaseInstance->expects($this->any())
            ->method('getConnection')
            ->willReturn($mockDb);

        $instanceProperty->setValue(null, $mockDatabaseInstance);

        // Instantiate controller after mock is set up
        $controller = new SampahController();

        // Capture output since controller extracts variables and requires files
        // Suppress warnings from constants already being defined (like BASE_URL)
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        // Assert that the title variable matches
        $this->assertStringContainsString('Kategori & Harga Sampah', $output);
        // Assert that the mocked data is somehow used/rendered
        $this->assertStringContainsString('Kardus', $output);
        $this->assertStringContainsString('Plastik', $output);

        // Reset the singleton instance for other tests
        $instanceProperty->setValue(null, null);
    }
}
