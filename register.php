<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#register', true, 303);
    exit;
}

function clean(string $key, int $limit = 160): string {
    $value = trim((string)($_POST[$key] ?? ''));
    return mb_substr($value, 0, $limit);
}

function safe(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Honeypot field: bots fill this; people never see it.
if (clean('company') !== '') {
    header('Location: index.html?registered=1#register', true, 303);
    exit;
}

$fullName = clean('fullName', 100);
$email = filter_var(clean('email'), FILTER_VALIDATE_EMAIL);
$countryCode = clean('countryCode', 8);
$mobile = preg_replace('/\D+/', '', clean('mobile', 15));
$consent = isset($_POST['consent']);
$allowedInterests = ['Beaches', 'Mountains', 'Culture', 'International'];
$submittedInterests = $_POST['interests'] ?? [];
$interests = array_values(array_intersect(
    $allowedInterests,
    is_array($submittedInterests) ? $submittedInterests : []
));

if (
    mb_strlen($fullName) < 2 ||
    $email === false ||
    !preg_match('/^\+\d{1,4}$/', $countryCode) ||
    !preg_match('/^\d{7,15}$/', $mobile) ||
    !$consent
) {
    header('Location: index.html?error=1#register', true, 303);
    exit;
}

$storageDirectory = __DIR__ . '/storage';
if (!is_dir($storageDirectory)) {
    mkdir($storageDirectory, 0750, true);
}

$record = [
    gmdate('c'),
    $fullName,
    $email,
    $countryCode,
    $mobile,
    implode(', ', $interests),
    'yes',
];

$file = fopen($storageDirectory . '/registrations.csv', 'ab');
if ($file !== false) {
    if (flock($file, LOCK_EX)) {
        fputcsv($file, $record);
        flock($file, LOCK_UN);
    }
    fclose($file);
}

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: Travel Bridge <hello@travelbridge.in>',
    'Reply-To: ' . $email,
];

$companyMessage = '<h2>New Travel Bridge registration</h2>'
    . '<p><b>Name:</b> ' . safe($fullName) . '</p>'
    . '<p><b>Email:</b> ' . safe((string)$email) . '</p>'
    . '<p><b>Mobile:</b> ' . safe($countryCode . ' ' . $mobile) . '</p>'
    . '<p><b>Interests:</b> ' . safe(implode(', ', $interests) ?: 'Not specified') . '</p>';

@mail(
    'sales@travelbridge.in',
    'New launch registration · ' . $fullName,
    $companyMessage,
    implode("\r\n", $headers)
);

$customerHeaders = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: Travel Bridge <hello@travelbridge.in>',
];
$welcomeMessage = '<div style="margin:0;background:#fbf7ee;padding:36px;font-family:Arial,sans-serif;color:#000b1f">'
    . '<div style="max-width:600px;margin:auto;background:#fff;border-radius:18px;overflow:hidden">'
    . '<div style="background:#000b1f;padding:28px;color:#e3aa25;font-size:22px;font-weight:700">Travel Bridge</div>'
    . '<div style="padding:34px"><p style="color:#9b6f00;font-size:12px;text-transform:uppercase;font-weight:700">Your next adventure, first</p>'
    . '<h1 style="font-size:30px">Welcome aboard, ' . safe($fullName) . '!</h1>'
    . '<p style="line-height:1.7;color:#586171">Thank you for registering with Travel Bridge. We’ll be launching soon and will keep you updated about more offers, holiday packages and travel deals.</p>'
    . '<p style="line-height:1.7;color:#586171">Great journeys are just over the horizon.</p>'
    . '<p style="margin-top:28px">Warmly,<br><b>The Travel Bridge team</b></p></div>'
    . '<div style="background:#e3aa25;height:7px"></div></div></div>';

@mail(
    (string)$email,
    'Welcome aboard · Travel Bridge is launching soon',
    $welcomeMessage,
    implode("\r\n", $customerHeaders)
);

header('Location: index.html?registered=1#register', true, 303);
exit;
