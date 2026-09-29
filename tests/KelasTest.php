<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/kelas.php';

class KelasTest extends TestCase {
    public function testGetAll() {
        // Create mock for PDOStatement
        $stmtMock = $this->createMock(\PDOStatement::class);

        $expectedData = [
            ['id' => 1, 'nama_kelas' => '10A', 'nama_wali' => 'Budi', 'total_siswa' => 30],
            ['id' => 2, 'nama_kelas' => '10B', 'nama_wali' => 'Andi', 'total_siswa' => 25],
        ];

        // Configure PDOStatement mock
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedData);

        // Create mock for PDO
        $pdoMock = $this->createMock(\PDO::class);

        $expectedSql = "SELECT k.*, u.nama AS nama_wali,
               (SELECT COUNT(id) FROM users WHERE kelas_id = k.id AND role = 'siswa') AS total_siswa
               FROM kelas k
               LEFT JOIN users u ON k.walikelas_id = u.id
               ORDER BY k.nama_kelas ASC";

        // Configure PDO mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($expectedSql)
                ->willReturn($stmtMock);

        // Instantiate Kelas model with mocked PDO
        $kelas = new Kelas($pdoMock);

        // Call the method to test
        $result = $kelas->getAll();

        // Assert the result matches the expected data
        $this->assertEquals($expectedData, $result);
    }
}