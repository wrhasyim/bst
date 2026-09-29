<?php
// tests/PenjualanControllerTest.php

if (!defined('BASE_URL')) define('BASE_URL', 'http://localhost');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once __DIR__ . '/../app/Controllers/PenjualanController.php';
require_once __DIR__ . '/../app/Core/Database.php';

class PenjualanMockPDO {
    public $queryCallCount = 0;
    public $queries = [];

    public function query($sql) {
        $this->queryCallCount++;
        $this->queries[] = $sql;

        if (strpos($sql, 'k.konversi_kg') !== false && strpos($sql, '1 as konversi_kg') === false) {
            // Throw exception for first query to trigger catch block
            throw new Exception("Column konversi_kg not found");
        }

        return new PenjualanMockPDOStatement();
    }

    public function prepare($sql) {
        return new PenjualanMockPDOStatement();
    }
}

class PenjualanMockPDOStatement {
    public function fetchAll() {
        return [
            [
                'id' => 1,
                'kategori_id' => 1,
                'tanggal_jual' => '2023-10-10 10:00:00',
                'keterangan' => 'Test',
                'total_pcs' => 10,
                'konversi_kg' => 1,
                'nama_sampah' => 'Plastik',
                'satuan' => 'kg',
                'total_pendapatan' => 10000,
                'kas_tutup_botol_rp' => 0,
                'margin_total_rp' => 2000,
                'beban_nasabah_rp' => 8000,
                'kas_sekolah_rp' => 1000,
                'honor_pengelola_rp' => 500,
                'honor_piket_rp' => 500,
                'kas_bst_rp' => 0
            ]
        ];
    }
    public function execute($params = null) {
        return true;
    }
}

class PenjualanMockDatabase {
    private $conn;
    public function __construct() {
        $this->conn = new PenjualanMockPDO();
    }
    public function getConnection() {
        return $this->conn;
    }
}

function runPenjualanControllerTest() {
    // Save previous state
    $prevSession = $_SESSION ?? [];

    // Set session vars to bypass auth
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['role'] = 'admin';
    $_SESSION['user_id'] = 1;

    $mockDb = new PenjualanMockDatabase();
    $reflection = new ReflectionClass('Database');
    $property = $reflection->getProperty('instance');
    $property->setAccessible(true);

    // Save original instance
    $originalInstance = $property->getValue();
    $property->setValue(null, $mockDb);

    ob_start();
    $controller = new PenjualanController();
    $controller->index();
    $output = ob_get_clean();

    $pdo = $mockDb->getConnection();
    $success = false;
    if ($pdo->queryCallCount === 2 && strpos($pdo->queries[1], '1 as konversi_kg') !== false) {
        echo "✅ Test Passed: Exception in index() was caught and fallback query was executed.\n";
        $success = true;
    } else {
        echo "❌ Test Failed: Expected 2 queries with fallback, got {$pdo->queryCallCount}\n";
        print_r($pdo->queries);
    }

    // Restore state
    $property->setValue(null, $originalInstance);
    $_SESSION = $prevSession;

    if (!$success) {
        throw new Exception("PenjualanControllerTest failed.");
    }
}

// Run if executed directly
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    runPenjualanControllerTest();
}
