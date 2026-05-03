<?php
declare(strict_types=1);

// #region agent log (debug-mode)
$logPath = __DIR__ . '/../debug-01db3a.log';
$payload = [
    'sessionId' => '01db3a',
    'runId' => 'probe',
    'hypothesisId' => 'H9',
    'location' => 'auth.v2/__debug_probe.php:hit',
    'message' => 'Probe endpoint hit',
    'data' => [
        'requestUri' => $_SERVER['REQUEST_URI'] ?? null,
        'host' => $_SERVER['HTTP_HOST'] ?? null,
        'scriptFilename' => $_SERVER['SCRIPT_FILENAME'] ?? null,
        'documentRoot' => $_SERVER['DOCUMENT_ROOT'] ?? null,
        'logPath' => $logPath,
    ],
    'timestamp' => (int) floor(microtime(true) * 1000),
];
$jsonLine = json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL;
$writeOk = false;
$writeErr = null;
try {
    $bytes = file_put_contents($logPath, $jsonLine, FILE_APPEND);
    $writeOk = ($bytes !== false);
} catch (Throwable $e) {
    $writeErr = $e->getMessage();
}
error_log('[01db3a probe] writeOk=' . ($writeOk ? '1' : '0') . ' logPath=' . $logPath . ($writeErr ? (' err=' . $writeErr) : ''));
// #endregion agent log (debug-mode)

header('Content-Type: text/plain; charset=utf-8');
echo "OK probe 01db3a\n";
echo "SCRIPT_FILENAME=" . ($_SERVER['SCRIPT_FILENAME'] ?? '') . "\n";
echo "DOCUMENT_ROOT=" . ($_SERVER['DOCUMENT_ROOT'] ?? '') . "\n";
echo "LOG_PATH=" . $logPath . "\n";
echo "LOG_WRITE_OK=" . ($writeOk ? '1' : '0') . "\n";
echo "LOG_WRITE_ERR=" . ($writeErr ?? '') . "\n";
