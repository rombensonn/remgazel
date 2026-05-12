<?php

declare(strict_types=1);

ini_set('display_errors', '0');
error_reporting(E_ALL);

$config = require __DIR__ . '/../config.php';
date_default_timezone_set($config['site']['timezone'] ?? 'Europe/Moscow');

$storageDir = dirname(__DIR__) . '/storage';
$leadFile = $storageDir . '/leads.csv';
$rateFile = $storageDir . '/rate_limit.json';
$errorLog = $storageDir . '/error.log';

function log_error_message(string $message): void
{
    global $errorLog;
    @file_put_contents($errorLog, '[' . date('c') . '] ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

set_exception_handler(static function (Throwable $e): void {
    log_error_message($e->getMessage());
    respond(false, 'Не удалось отправить заявку. Позвоните в сервис, если вопрос срочный.', 500);
});

function respond(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function clean_value(mixed $value, int $limit = 1000): string
{
    $value = is_scalar($value) ? (string) $value : '';
    $value = trim(strip_tags($value));
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($value, 'UTF-8') > $limit) {
            $value = mb_substr($value, 0, $limit, 'UTF-8');
        }
        return $value;
    }
    if (strlen($value) > $limit * 4) {
        $value = substr($value, 0, $limit * 4);
    }
    return $value;
}

function is_phone_valid(string $phone): bool
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    return strlen($digits) >= 10 && strlen($digits) <= 15;
}

function client_ip(): string
{
    $candidates = [
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '',
        $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    ];
    $ip = trim(explode(',', $candidates[0] ?: $candidates[1] ?: $candidates[2])[0]);
    return $ip !== '' ? $ip : 'unknown';
}

function rate_limit_check(string $ip, string $rateFile, int $window, int $max): bool
{
    $now = time();
    $hash = hash('sha256', $ip);
    $data = [];

    if (is_file($rateFile)) {
        $decoded = json_decode((string) file_get_contents($rateFile), true);
        $data = is_array($decoded) ? $decoded : [];
    }

    foreach ($data as $key => $record) {
        if (!isset($record['start']) || ($now - (int) $record['start']) > $window) {
            unset($data[$key]);
        }
    }

    $record = $data[$hash] ?? ['start' => $now, 'count' => 0];
    if (($now - (int) $record['start']) > $window) {
        $record = ['start' => $now, 'count' => 0];
    }

    $record['count'] = (int) $record['count'] + 1;
    $data[$hash] = $record;
    @file_put_contents($rateFile, json_encode($data), LOCK_EX);

    return $record['count'] <= $max;
}

function send_telegram(array $lead, array $config, string $message): void
{
    $token = $config['lead']['telegram_bot_token'] ?? '';
    $chatId = $config['lead']['telegram_chat_id'] ?? '';
    if ($token === '' || $chatId === '') {
        return;
    }

    $payload = http_build_query([
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => 'true',
    ]);

    $url = 'https://api.telegram.org/bot' . rawurlencode($token) . '/sendMessage';
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 6,
        ],
    ]);

    $result = @file_get_contents($url, false, $context);
    if ($result === false) {
        log_error_message('Telegram notification failed for phone ' . $lead['phone']);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Метод запроса не поддерживается.', 405);
}

if (!is_dir($storageDir) && !mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
    respond(false, 'Не удалось подготовить хранение заявок.', 500);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$rawInput = (string) file_get_contents('php://input');
$payload = [];

if (stripos($contentType, 'application/json') !== false) {
    $decoded = json_decode($rawInput, true);
    $payload = is_array($decoded) ? $decoded : [];
} else {
    $payload = $_POST;
}

if (clean_value($payload['website'] ?? '', 120) !== '') {
    respond(true, 'Заявка отправлена. Мастер свяжется с вами в ближайшее время.');
}

$ip = client_ip();
$leadConfig = $config['lead'];
if (!rate_limit_check($ip, $rateFile, (int) $leadConfig['rate_limit_window'], (int) $leadConfig['rate_limit_max'])) {
    respond(false, 'Слишком много заявок за короткое время. Попробуйте позже или позвоните в сервис.', 429);
}

$name = clean_value($payload['name'] ?? '', 80);
$phone = clean_value($payload['phone'] ?? '', 40);
$car = clean_value($payload['car'] ?? '', 80);
$issue = clean_value($payload['issue'] ?? '', 1000);
$contactMethod = clean_value($payload['contact_method'] ?? 'Звонок', 40);
$pageUrl = clean_value($payload['page_url'] ?? '', 300);
$utmSource = clean_value($payload['utm_source'] ?? '', 120);
$utmMedium = clean_value($payload['utm_medium'] ?? '', 120);
$utmCampaign = clean_value($payload['utm_campaign'] ?? '', 120);
$vehicleStatus = clean_value($payload['vehicle_status'] ?? '', 40);
$preferredTime = clean_value($payload['preferred_time'] ?? '', 80);

if ($phone === '' || !is_phone_valid($phone)) {
    respond(false, 'Укажите корректный телефон, чтобы мастер мог связаться с вами.', 422);
}

if ($name === '' && $car === '' && $issue === '') {
    respond(false, 'Добавьте марку авто или коротко опишите проблему.', 422);
}

if ($vehicleStatus !== '' || $preferredTime !== '') {
    $extra = [];
    if ($vehicleStatus !== '') {
        $extra[] = 'Машина на ходу: ' . $vehicleStatus;
    }
    if ($preferredTime !== '') {
        $extra[] = 'Удобно приехать: ' . $preferredTime;
    }
    $issue = trim($issue . PHP_EOL . implode(PHP_EOL, $extra));
}

$createdAt = date('c');
$lead = [
    'name' => $name,
    'phone' => $phone,
    'car' => $car,
    'issue' => $issue,
    'contact_method' => $contactMethod,
    'page_url' => $pageUrl,
    'utm_source' => $utmSource,
    'utm_medium' => $utmMedium,
    'utm_campaign' => $utmCampaign,
    'created_at' => $createdAt,
];

$isNewFile = !is_file($leadFile) || filesize($leadFile) === 0;
$handle = fopen($leadFile, 'ab');
if ($handle === false) {
    respond(false, 'Не удалось сохранить заявку. Позвоните в сервис, если вопрос срочный.', 500);
}

if ($isNewFile) {
    fputcsv($handle, array_keys($lead), ';');
}
fputcsv($handle, $lead, ';');
fclose($handle);

$utmLine = trim(implode(' / ', array_filter([$utmSource, $utmMedium, $utmCampaign])));
$message = "Новая заявка с сайта РемГазель\n\n"
    . "Имя: " . ($name !== '' ? $name : 'не указано') . "\n"
    . "Телефон: " . $phone . "\n"
    . "Марка авто: " . ($car !== '' ? $car : 'не указано') . "\n"
    . "Проблема: " . ($issue !== '' ? $issue : 'не указано') . "\n"
    . "Удобный способ связи: " . $contactMethod . "\n"
    . "Страница: " . ($pageUrl !== '' ? $pageUrl : 'не указана') . "\n"
    . "UTM: " . ($utmLine !== '' ? $utmLine : 'нет') . "\n"
    . "Дата: " . $createdAt . "\n";

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: РемГазель <' . ($leadConfig['email_from'] ?? 'no-reply@localhost') . '>',
];

$emailTo = (string) ($leadConfig['email_to'] ?? '');
if ($emailTo !== '' && $emailTo !== 'mail@example.com') {
    $sent = @mail($emailTo, (string) $leadConfig['email_subject'], $message, implode("\r\n", $headers));
    if (!$sent) {
        log_error_message('Email notification failed for phone ' . $phone);
    }
}

$telegramMessage = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
send_telegram($lead, $config, $telegramMessage);

respond(true, 'Заявка отправлена. Мастер свяжется с вами в ближайшее время.');
