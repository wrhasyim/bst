<?php

// Use SQLite in-memory for benchmark
$db = new PDO('sqlite::memory:', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$db->beginTransaction();
$db->exec("CREATE TABLE benchmark_penarikan (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, jumlah DECIMAL(10,2), keterangan VARCHAR(255))");
$db->commit();

$count = 10000;

// Baseline: prepare inside loop
$start = microtime(true);
$db->beginTransaction();
for ($i = 0; $i < $count; $i++) {
    $stmt = $db->prepare("INSERT INTO benchmark_penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
    $stmt->execute([$i, 100, 'Test inside']);
}
$db->commit();
$timeInside = microtime(true) - $start;

$db->exec("DELETE FROM benchmark_penarikan");

// Improved: prepare outside loop
$start = microtime(true);
$db->beginTransaction();
$stmt2 = $db->prepare("INSERT INTO benchmark_penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
for ($i = 0; $i < $count; $i++) {
    $stmt2->execute([$i, 100, 'Test outside']);
}
$db->commit();
$timeOutside = microtime(true) - $start;

echo "Baseline (Prepare inside loop): " . round($timeInside * 1000, 2) . " ms\n";
echo "Improved (Prepare outside loop): " . round($timeOutside * 1000, 2) . " ms\n";
echo "Improvement: " . round((($timeInside - $timeOutside) / $timeInside) * 100, 2) . "% faster\n";
