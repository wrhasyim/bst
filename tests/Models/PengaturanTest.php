<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Models/Pengaturan.php';
require_once __DIR__ . '/../../app/Core/Database.php';

class PengaturanTest extends TestCase
{
    private $dbMock;
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void
    {
        // Mock the PDOStatement
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Mock the PDO instance
        $this->pdoMock = $this->createMock(PDO::class);

        // Mock the Database singleton
        $this->dbMock = $this->createMock(Database::class);
        $this->dbMock->method('getConnection')->willReturn($this->pdoMock);

        // Inject the mocked Database instance into the singleton property using Reflection
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, $this->dbMock);
    }

    protected function tearDown(): void
    {
        // Reset the singleton instance after each test to avoid polluting other tests
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, null);
    }

    public function testGetAllSettingsReturnsSettings()
    {
        // Arrange
        $expectedRows = [
            ['kunci' => 'app_name', 'nilai' => 'Test App'],
            ['kunci' => 'app_version', 'nilai' => '1.0.0'],
        ];

        // Mock the fetch method to return rows and then false to stop iteration
        $this->stmtMock->expects($this->exactly(3))
            ->method('fetch')
            ->willReturn(
                $expectedRows[0],
                $expectedRows[1],
                false
            );

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with("SELECT kunci, nilai FROM pengaturan")
            ->willReturn($this->stmtMock);

        $pengaturan = new Pengaturan();

        // Act
        $settings = $pengaturan->getAllSettings();

        // Assert
        $expectedResult = [
            'app_name' => 'Test App',
            'app_version' => '1.0.0',
        ];
        $this->assertEquals($expectedResult, $settings);
    }

    public function testGetAllSettingsReturnsEmptyArrayWhenNoSettings()
    {
        // Arrange
        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with("SELECT kunci, nilai FROM pengaturan")
            ->willReturn($this->stmtMock);

        $pengaturan = new Pengaturan();

        // Act
        $settings = $pengaturan->getAllSettings();

        // Assert
        $this->assertEquals([], $settings);
    }
}
