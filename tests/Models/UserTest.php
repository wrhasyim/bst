<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Models/User.php';
require_once __DIR__ . '/../../app/Core/Database.php';

class UserTest extends TestCase {
    private $dbMock;
    private $pdoStmtMock;
    private $pdoMock;

    protected function setUp(): void {
        // We need to mock the Database singleton
        $this->pdoMock = $this->createMock(PDO::class);
        $this->pdoStmtMock = $this->createMock(PDOStatement::class);

        $this->dbMock = $this->createMock(Database::class);
        $this->dbMock->method('getConnection')->willReturn($this->pdoMock);

        // Use Reflection to override the singleton instance
        $ref = new ReflectionClass(Database::class);
        $prop = $ref->getProperty('instance');
        $prop->setAccessible(true);
        $prop->setValue(null, $this->dbMock);
    }

    protected function tearDown(): void {
        // Reset the singleton
        $ref = new ReflectionClass(Database::class);
        $prop = $ref->getProperty('instance');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    public function testCreateSuccess() {
        // Arrange
        $userData = [
            'nama' => 'John Doe',
            'username' => 'johndoe',
            'password' => 'hashed_password',
            'role' => 'siswa',
            'kelas_id' => 1,
            'angkatan' => 2023,
            'is_active' => 1
        ];

        // Ensure prepare is called with correct SQL
        $expectedSql = "INSERT INTO users (nama, username, password, role, kelas_id, angkatan, is_active)
                VALUES (:nama, :username, :password, :role, :kelas_id, :angkatan, :is_active)";

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($expectedSql)
            ->willReturn($this->pdoStmtMock);

        // Ensure execute is called with user data
        $this->pdoStmtMock->expects($this->once())
            ->method('execute')
            ->with($userData)
            ->willReturn(true);

        // Act
        $userModel = new User();
        $result = $userModel->create($userData);

        // Assert
        $this->assertTrue($result);
    }

    public function testCreateFailure() {
        // Arrange
        $userData = [
            'nama' => 'Jane Doe',
            'username' => 'janedoe',
            'password' => 'hashed_password',
            'role' => 'siswa',
            'kelas_id' => 2,
            'angkatan' => 2024,
            'is_active' => 1
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->pdoStmtMock);

        // Ensure execute returns false when there's an error
        $this->pdoStmtMock->expects($this->once())
            ->method('execute')
            ->with($userData)
            ->willReturn(false);

        // Act
        $userModel = new User();
        $result = $userModel->create($userData);

        // Assert
        $this->assertFalse($result);
    }
}
