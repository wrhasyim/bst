<?php
// Simple syntax check helper
function lint_file($filename) {
    exec("php -l " . escapeshellarg($filename), $output, $return_var);
    return $return_var === 0;
}
echo "Checking SetoranController.php... " . (lint_file("app/Controllers/SetoranController.php") ? "OK" : "FAIL") . "\n";
echo "Checking LaporanController.php... " . (lint_file("app/Controllers/LaporanController.php") ? "OK" : "FAIL") . "\n";
