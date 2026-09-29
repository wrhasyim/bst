<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/penjualan.php';

class PenjualanTest extends TestCase {
    public function testGetReadyStockWithKategoriId() {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
                 ->method('execute')
                 ->with(['kat_id' => 1]);
        $mockStmt->expects($this->once())
                 ->method('fetch')
                 ->willReturn(['kategori_id' => 1, 'total_stok' => 50]);

        $mockDb = $this->createMock(PDO::class);
        $mockDb->expects($this->once())
               ->method('prepare')
               ->with("SELECT kategori_id, SUM(berat) as total_stok \n                FROM setoran \n                WHERE status = 'valid' AND is_sold = 0 AND kategori_id = :kat_id GROUP BY kategori_id")
               ->willReturn($mockStmt);

        $penjualan = (new ReflectionClass(Penjualan::class))->newInstanceWithoutConstructor();
        $property = (new ReflectionClass(Penjualan::class))->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($penjualan, $mockDb);

        $result = $penjualan->getReadyStock(1);
        $this->assertEquals(['kategori_id' => 1, 'total_stok' => 50], $result);
    }

    public function testGetReadyStockWithoutKategoriId() {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn([
                     ['kategori_id' => 1, 'total_stok' => 50],
                     ['kategori_id' => 2, 'total_stok' => 30]
                 ]);

        $mockDb = $this->createMock(PDO::class);
        $mockDb->expects($this->once())
               ->method('query')
               ->with("SELECT kategori_id, SUM(berat) as total_stok \n                FROM setoran \n                WHERE status = 'valid' AND is_sold = 0 GROUP BY kategori_id")
               ->willReturn($mockStmt);

        $penjualan = (new ReflectionClass(Penjualan::class))->newInstanceWithoutConstructor();
        $property = (new ReflectionClass(Penjualan::class))->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($penjualan, $mockDb);

        $result = $penjualan->getReadyStock();
        $this->assertEquals([
            ['kategori_id' => 1, 'total_stok' => 50],
            ['kategori_id' => 2, 'total_stok' => 30]
        ], $result);
    }
}
