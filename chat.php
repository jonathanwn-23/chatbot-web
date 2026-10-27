<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Gunakan metode POST.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$question = trim((string) ($data['question'] ?? ''));
if ($question === '' || mb_strlen($question) > 500) {
    http_response_code(400);
    echo json_encode(['error' => 'Pertanyaan wajib diisi (maks. 500 karakter).']);
    exit;
}

if (!function_exists('curl_init')) {
    http_response_code(500);
    echo json_encode(['error' => 'Ekstensi PHP cURL belum aktif.']);
    exit;
}

$ch = curl_init('http://127.0.0.1:5000/api/chat');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode(['question' => $question]),
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 90,
]);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Flask tidak dapat dihubungi.']);
    exit;
}

http_response_code($status >= 100 && $status <= 599 ? $status : 502);
echo $response;