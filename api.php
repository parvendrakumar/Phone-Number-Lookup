<?php
// NUMVERIFY API CONFIGURATION
define('NUMVERIFY_API_KEY', getenv('NUMVERIFY_API_KEY') ?: '');
define('NUMVERIFY_API_URL', 'https://apilayer.net/api/validate');

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['phoneNumber']) || empty($input['countryCode'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$phoneNumber = sanitizePhoneNumber($input['phoneNumber']);
$countryCode = sanitizeCountryCode($input['countryCode']);

// Validate API key
if (empty(NUMVERIFY_API_KEY) || NUMVERIFY_API_KEY === 'YOUR_API_KEY_HERE') {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'API key not configured']);
    exit;
}

// Call NumVerify API
$result = callNumVerifyAPI($phoneNumber, $countryCode);
echo json_encode($result);


/**
 * Call NumVerify API to validate phone number
 */
function callNumVerifyAPI($phoneNumber, $countryCode) {
    $url = NUMVERIFY_API_URL . '?' . http_build_query([
        'access_key'   => NUMVERIFY_API_KEY,
        'number'       => $phoneNumber,
        'country_code' => $countryCode,
        'format'       => 1
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['success' => false, 'error' => 'Curl request failed: ' . $error];
    }

    if ($httpCode !== 200) {
        return ['success' => false, 'error' => 'API returned status code: ' . $httpCode];
    }

    $data = json_decode($response, true);
    if (!$data || !isset($data['valid'])) {
        return ['success' => false, 'error' => 'No results found for this phone number'];
    }

    return [
        'success' => true,
        'data' => [
            'valid'          => $data['valid'],
            'number'         => $data['international_format'] ?? $phoneNumber,
            'local_format'   => $data['local_format'] ?? null,
            'country_prefix' => $data['country_prefix'] ?? null,
            'country_code'   => $data['country_code'] ?? $countryCode,
            'country_name'   => $data['country_name'] ?? 'Unknown',
            'location'       => $data['location'] ?? 'Unknown',
            'carrier'        => $data['carrier'] ?? 'Unknown',
            'line_type'      => $data['line_type'] ?? 'Unknown'
        ]
    ];
}

/**
 * Sanitize phone number input
 */
function sanitizePhoneNumber($phone) {
    $phone = preg_replace('/[^0-9+]/', '', trim($phone));
    if (!str_starts_with($phone, '+')) {
        $phone = '+' . ltrim($phone, '0');
    }
    return $phone;
}

/**
 * Sanitize country code input
 */
function sanitizeCountryCode($code) {
    return strtoupper(preg_replace('/[^A-Z]/', '', trim($code)));
}
?>
