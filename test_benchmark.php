<?php
$db = new PDO('sqlite::memory:');
$db->exec("CREATE TABLE pencairan_honor (id INTEGER PRIMARY KEY, user_id INTEGER, jenis TEXT, jumlah REAL)");

// Insert dummy data
$db->beginTransaction();
for ($i = 1; $i <= 1000; $i++) {
    $db->exec("INSERT INTO pencairan_honor (user_id, jenis, jumlah) VALUES ($i, 'walikelas', 100)");
    $db->exec("INSERT INTO pencairan_honor (user_id, jenis, jumlah) VALUES ($i, 'walikelas', 50)");
}
$db->commit();

// Dummy data_honor
$data_honor = [];
for ($i = 1; $i <= 1000; $i++) {
    $data_honor[] = ['user_id' => $i, 'total_jatah' => 500];
}

// Baseline: N+1
$start = microtime(true);
$data1 = $data_honor;
if (is_array($data1) && count($data1) > 0) {
    foreach ($data1 as &$h) {
        $user_id = $h['user_id'] ?? 0;
        $total_jatah = $h['total_jatah'] ?? 0;

        $stmtCair = $db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE user_id = ? AND jenis = 'walikelas'");
        $stmtCair->execute([$user_id]);
        $sudah_cair = $stmtCair->fetchColumn() ?: 0;

        $h['sudah_cair'] = $sudah_cair;
        $h['sisa_honor'] = $total_jatah - $sudah_cair;
    }
}
$end = microtime(true);
echo "N+1 Time: " . ($end - $start) . " seconds\n";

// Optimization: IN clause
$start = microtime(true);
$data2 = $data_honor;
if (is_array($data2) && count($data2) > 0) {
    $userIds = array_column($data2, 'user_id');
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));

    $stmtCair = $db->prepare("
        SELECT user_id, IFNULL(SUM(jumlah), 0) as total_cair
        FROM pencairan_honor
        WHERE jenis = 'walikelas' AND user_id IN ($placeholders)
        GROUP BY user_id
    ");
    $stmtCair->execute($userIds);
    $cairData = $stmtCair->fetchAll(PDO::FETCH_KEY_PAIR);

    foreach ($data2 as &$h) {
        $user_id = $h['user_id'] ?? 0;
        $total_jatah = $h['total_jatah'] ?? 0;

        $sudah_cair = $cairData[$user_id] ?? 0;
        $h['sudah_cair'] = $sudah_cair;
        $h['sisa_honor'] = $total_jatah - $sudah_cair;
    }
}
$end = microtime(true);
echo "Optimized Time: " . ($end - $start) . " seconds\n";

// Validate results
$is_same = json_encode($data1) === json_encode($data2);
echo "Results match: " . ($is_same ? "Yes" : "No") . "\n";
