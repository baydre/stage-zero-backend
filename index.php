<?php
// index.php

header('Content-Type: application/json');

// load .env (simple parser)
loadEnv(__DIR__ . '/.env');

function loadEnv(string $file): void {
    if (!is_readable($file)) {
        return;
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // remove surrounding quotes if present
        if (preg_match('/^([\'"])(.*)\1$/', $value, $m)) {
            $value = $m[2];
        }
        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

// use env variables with sensible fallbacks
$user = [
    "email" => getenv('USER_EMAIL') ?: 'baydreafrica@gmail.com',
    "name"  => getenv('USER_NAME')  ?: 'baydre afrika',
    "stack" => getenv('USER_STACK') ?: 'PHP'
];

function getCatFact(): string {
    $url = "https://catfact.ninja/fact";

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return "Could not fetch cat fact.";
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode == 200 && $response) {
            $data = json_decode($response, true);
            return $data['fact'] ?? "No fact found.";
        }

        return "Could not fetch cat fact.";
    } else {
        // Fallback using file_get_contents
        $ctx = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'timeout' => 5
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true
            ]
        ]);

        $response = @file_get_contents($url, false, $ctx);
        if ($response) {
            $data = json_decode($response, true);
            return $data['fact'] ?? "No fact found.";
        }

        return "Could not fetch cat fact.";
    }
}

$response = [
    "status" => "success",
    "user" => $user,
    "timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
    "fact" => getCatFact()
];

echo json_encode($response, JSON_PRETTY_PRINT);