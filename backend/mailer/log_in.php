<?php
declare(strict_types=1);

$payload = [
    'sessionId' => '01db3a',
    'runId' => 'post-fix',
    'hypothesisId' => 'H1',
    'location' => 'auth.v2/log_in.php:redirect',
    'message' => 'Legacy log_in.php hit; redirecting to login.php',
    'data' => [
        'requestUri' => $_SERVER['REQUEST_URI'] ?? null,
        'referer' => $_SERVER['HTTP_REFERER'] ?? null,
        'method' => $_SERVER['REQUEST_METHOD'] ?? null,
    ],
    'timestamp' => (int) floor(microtime(true) * 1000),
];
@file_put_contents(__DIR__ . '/../debug-01db3a.log', json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);

header('Location: login.php', true, 302);
exit;

