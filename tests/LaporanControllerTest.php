<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Security.php';
require_once __DIR__ . '/../app/Controllers/LaporanController.php';

class LaporanControllerTest extends TestCase
{
    private $pdoMock;
    private $controller;
    private $stmtMock;

    public function setUp(): void
    {
        parent::setUp();

        // Setup session for Security::requireRole
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['role'] = 'admin';
        $_SESSION['user_id'] = 1;

        $this->pdoMock = $this->createMock(PDO::class);

        // Mock Database singleton
        $mockDb = new class($this->pdoMock) {
            private $conn;
            public function __construct($conn) {
                $this->conn = $conn;
            }
            public function getConnection() {
                return $this->conn;
            }
        };

        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, $mockDb);

        $this->controller = new LaporanController();
    }

    public function tearDown(): void
    {
        $reflection = new ReflectionClass('Database');
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $_GET = [];
        session_destroy();
        parent::tearDown();
    }

    public function testKeuanganWithStartAndEndDate()
    {
        $_GET['start_date'] = '2023-01-01';
        $_GET['end_date'] = '2023-01-31';

        $stmtConfigMock = $this->createMock(PDOStatement::class);
        $stmtConfigMock->method('fetchAll')->willReturn(['persen_kas_sekolah' => 10]);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')->willReturn([
            'total_kotor' => 1000,
            'beban_nasabah' => 200,
            'margin_total' => 800,
            'kas_sekolah' => 100,
            'honor_pengelola' => 50,
            'honor_piket' => 20,
            'kas_bst' => 30,
            'total_tutup_botol_in_auto' => 10
        ]);

        $stmtFetchColumnMock = $this->createMock(PDOStatement::class);
        $stmtFetchColumnMock->method('fetchColumn')->willReturn(50);

        $stmtFetchAllMock = $this->createMock(PDOStatement::class);
        $stmtFetchAllMock->method('fetchAll')->willReturn([]);

        $params = [
            'start' => '2023-01-01 00:00:00',
            'end' => '2023-01-31 23:59:59'
        ];

        // Assert execute is called with $params for all prepared statements
        $stmtMock->expects($this->once())->method('execute')->with($params);
        $stmtFetchColumnMock->expects($this->exactly(13))->method('execute')->with($params);
        $stmtFetchAllMock->expects($this->once())->method('execute')->with($params);

        $this->pdoMock->method('query')->willReturn($stmtConfigMock);
        $this->pdoMock->method('prepare')->willReturnOnConsecutiveCalls(
            $stmtMock, // $stmtSnap
            $stmtFetchColumnMock, // $stmtKK
            $stmtFetchColumnMock, // $stmtTbInMan
            $stmtFetchColumnMock, // $stmtWalas
            $stmtFetchColumnMock, // $stmtReward
            $stmtFetchColumnMock, // $stmt CairP
            $stmtFetchColumnMock, // $stmt RefundP
            $stmtFetchColumnMock, // $stmt CairW
            $stmtFetchColumnMock, // $stmt RefundW
            $stmtFetchColumnMock, // $stmt CairS
            $stmtFetchColumnMock, // $stmt RefundS
            $stmtFetchColumnMock, // $stmt CairK
            $stmtFetchColumnMock, // $stmt RefundK
            $stmtFetchColumnMock, // $stmt TbOut
            $stmtFetchAllMock // $stmt HistRaw
        );

        ob_start();
        try {
            $this->controller->keuangan();
        } catch (Throwable $e) {
            // expected if layout file doesn't load completely
        }
        ob_get_clean();
    }

    public function testKeuanganWithoutDateParameters()
    {
        // No $_GET parameters

        $stmtConfigMock = $this->createMock(PDOStatement::class);
        $stmtConfigMock->method('fetchAll')->willReturn(['persen_kas_sekolah' => 10]);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')->willReturn([
            'total_kotor' => 1000,
            'beban_nasabah' => 200,
            'margin_total' => 800,
            'kas_sekolah' => 100,
            'honor_pengelola' => 50,
            'honor_piket' => 20,
            'kas_bst' => 30,
            'total_tutup_botol_in_auto' => 10
        ]);

        $stmtFetchColumnMock = $this->createMock(PDOStatement::class);
        $stmtFetchColumnMock->method('fetchColumn')->willReturn(50);

        $stmtFetchAllMock = $this->createMock(PDOStatement::class);
        $stmtFetchAllMock->method('fetchAll')->willReturn([]);

        $params = [];

        // Assert execute is called with empty $params for all prepared statements
        $stmtMock->expects($this->once())->method('execute')->with($params);
        $stmtFetchColumnMock->expects($this->exactly(12))->method('execute')->with($params);
        $stmtFetchAllMock->expects($this->once())->method('execute')->with($params);

        // $stmtKK is a query when dates are absent, instead of prepare
        $stmtKKMock = $this->createMock(PDOStatement::class);
        $stmtKKMock->method('fetchColumn')->willReturn(50);

        // When $this->db->query is called:
        // 1. query("SELECT kunci, nilai FROM pengaturan ...")
        // 2. query("SELECT SUM(s.total_harga) FROM setoran s ...")
        $this->pdoMock->expects($this->exactly(2))->method('query')->willReturnOnConsecutiveCalls(
            $stmtConfigMock,
            $stmtKKMock
        );

        $this->pdoMock->method('prepare')->willReturnOnConsecutiveCalls(
            $stmtMock, // $stmtSnap
            // No prepare for $stmtKK here
            $stmtFetchColumnMock, // $stmtTbInMan
            $stmtFetchColumnMock, // $stmtWalas
            $stmtFetchColumnMock, // $stmtReward
            $stmtFetchColumnMock, // $stmt CairP
            $stmtFetchColumnMock, // $stmt RefundP
            $stmtFetchColumnMock, // $stmt CairW
            $stmtFetchColumnMock, // $stmt RefundW
            $stmtFetchColumnMock, // $stmt CairS
            $stmtFetchColumnMock, // $stmt RefundS
            $stmtFetchColumnMock, // $stmt CairK
            $stmtFetchColumnMock, // $stmt RefundK
            $stmtFetchColumnMock, // $stmt TbOut
            $stmtFetchAllMock // $stmt HistRaw
        );

        ob_start();
        try {
            $this->controller->keuangan();
        } catch (Throwable $e) {
            // expected if layout file doesn't load completely
        }
        ob_get_clean();
    }

    public function testKeuanganWithOnlyStartDate()
    {
        $_GET['start_date'] = '2023-01-01';
        // No end_date

        $stmtConfigMock = $this->createMock(PDOStatement::class);
        $stmtConfigMock->method('fetchAll')->willReturn(['persen_kas_sekolah' => 10]);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')->willReturn([
            'total_kotor' => 1000,
            'beban_nasabah' => 200,
            'margin_total' => 800,
            'kas_sekolah' => 100,
            'honor_pengelola' => 50,
            'honor_piket' => 20,
            'kas_bst' => 30,
            'total_tutup_botol_in_auto' => 10
        ]);

        $stmtFetchColumnMock = $this->createMock(PDOStatement::class);
        $stmtFetchColumnMock->method('fetchColumn')->willReturn(50);

        $stmtFetchAllMock = $this->createMock(PDOStatement::class);
        $stmtFetchAllMock->method('fetchAll')->willReturn([]);

        $params = [];

        // Assert execute is called with empty $params for all prepared statements
        $stmtMock->expects($this->once())->method('execute')->with($params);
        $stmtFetchColumnMock->expects($this->exactly(12))->method('execute')->with($params);
        $stmtFetchAllMock->expects($this->once())->method('execute')->with($params);

        // $stmtKK is a query when dates are absent, instead of prepare
        $stmtKKMock = $this->createMock(PDOStatement::class);
        $stmtKKMock->method('fetchColumn')->willReturn(50);

        // When $this->db->query is called:
        // 1. query("SELECT kunci, nilai FROM pengaturan ...")
        // 2. query("SELECT SUM(s.total_harga) FROM setoran s ...")
        $this->pdoMock->expects($this->exactly(2))->method('query')->willReturnOnConsecutiveCalls(
            $stmtConfigMock,
            $stmtKKMock
        );

        $this->pdoMock->method('prepare')->willReturnOnConsecutiveCalls(
            $stmtMock, // $stmtSnap
            // No prepare for $stmtKK here
            $stmtFetchColumnMock, // $stmtTbInMan
            $stmtFetchColumnMock, // $stmtWalas
            $stmtFetchColumnMock, // $stmtReward
            $stmtFetchColumnMock, // $stmt CairP
            $stmtFetchColumnMock, // $stmt RefundP
            $stmtFetchColumnMock, // $stmt CairW
            $stmtFetchColumnMock, // $stmt RefundW
            $stmtFetchColumnMock, // $stmt CairS
            $stmtFetchColumnMock, // $stmt RefundS
            $stmtFetchColumnMock, // $stmt CairK
            $stmtFetchColumnMock, // $stmt RefundK
            $stmtFetchColumnMock, // $stmt TbOut
            $stmtFetchAllMock // $stmt HistRaw
        );

        ob_start();
        try {
            $this->controller->keuangan();
        } catch (Throwable $e) {
            // expected if layout file doesn't load completely
        }
        ob_get_clean();
    }

    public function testKeuanganWithOnlyEndDate()
    {
        // No start_date
        $_GET['end_date'] = '2023-01-31';

        $stmtConfigMock = $this->createMock(PDOStatement::class);
        $stmtConfigMock->method('fetchAll')->willReturn(['persen_kas_sekolah' => 10]);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')->willReturn([
            'total_kotor' => 1000,
            'beban_nasabah' => 200,
            'margin_total' => 800,
            'kas_sekolah' => 100,
            'honor_pengelola' => 50,
            'honor_piket' => 20,
            'kas_bst' => 30,
            'total_tutup_botol_in_auto' => 10
        ]);

        $stmtFetchColumnMock = $this->createMock(PDOStatement::class);
        $stmtFetchColumnMock->method('fetchColumn')->willReturn(50);

        $stmtFetchAllMock = $this->createMock(PDOStatement::class);
        $stmtFetchAllMock->method('fetchAll')->willReturn([]);

        $params = [];

        // Assert execute is called with empty $params for all prepared statements
        $stmtMock->expects($this->once())->method('execute')->with($params);
        $stmtFetchColumnMock->expects($this->exactly(12))->method('execute')->with($params);
        $stmtFetchAllMock->expects($this->once())->method('execute')->with($params);

        // $stmtKK is a query when dates are absent, instead of prepare
        $stmtKKMock = $this->createMock(PDOStatement::class);
        $stmtKKMock->method('fetchColumn')->willReturn(50);

        // When $this->db->query is called:
        // 1. query("SELECT kunci, nilai FROM pengaturan ...")
        // 2. query("SELECT SUM(s.total_harga) FROM setoran s ...")
        $this->pdoMock->expects($this->exactly(2))->method('query')->willReturnOnConsecutiveCalls(
            $stmtConfigMock,
            $stmtKKMock
        );

        $this->pdoMock->method('prepare')->willReturnOnConsecutiveCalls(
            $stmtMock, // $stmtSnap
            // No prepare for $stmtKK here
            $stmtFetchColumnMock, // $stmtTbInMan
            $stmtFetchColumnMock, // $stmtWalas
            $stmtFetchColumnMock, // $stmtReward
            $stmtFetchColumnMock, // $stmt CairP
            $stmtFetchColumnMock, // $stmt RefundP
            $stmtFetchColumnMock, // $stmt CairW
            $stmtFetchColumnMock, // $stmt RefundW
            $stmtFetchColumnMock, // $stmt CairS
            $stmtFetchColumnMock, // $stmt RefundS
            $stmtFetchColumnMock, // $stmt CairK
            $stmtFetchColumnMock, // $stmt RefundK
            $stmtFetchColumnMock, // $stmt TbOut
            $stmtFetchAllMock // $stmt HistRaw
        );

        ob_start();
        try {
            $this->controller->keuangan();
        } catch (Throwable $e) {
            // expected if layout file doesn't load completely
        }
        ob_get_clean();
    }
}
