<?php

// Basic script to download address data
// Run with: php download_address.php

$baseUrl = 'https://production.cas.so/address-kit/latest';
$storagePath = __DIR__ . '/storage/app/json_address';
$communesPath = $storagePath . '/communes';

if (!file_exists($communesPath)) {
    mkdir($communesPath, 0777, true);
}

function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local dev ease
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch) . "\n";
        return null;
    }
    curl_close($ch);
    return json_decode($output, true);
}

echo "Downloading Provinces...\n";
$provincesData = fetchData("$baseUrl/provinces");

// Check if data is wrapped in 'data' key or 'provinces' key or is direct array
$provinces = null;
if (isset($provincesData['data']) && is_array($provincesData['data'])) {
    $provinces = $provincesData['data'];
} elseif (isset($provincesData['provinces']) && is_array($provincesData['provinces'])) {
    $provinces = $provincesData['provinces'];
} elseif (is_array($provincesData)) {
    // Check if it's a list of provinces (indexed array) or an object (associative array)
    if (array_keys($provincesData) === range(0, count($provincesData) - 1)) {
        $provinces = $provincesData;
    }
}

if ($provinces) {
    // Save provinces.json
    file_put_contents("$storagePath/provinces.json", json_encode($provinces, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Saved " . count($provinces) . " provinces.\n";

    foreach ($provinces as $province) {
        if (!is_array($province)) {
            continue;
        }
        $code = $province['code'] ?? null; 
        $name = $province['name'] ?? 'Unknown';
        if (!$code) {
            continue;
        }
        echo "Downloading communes for $name ($code)...\n";
        
        $communesData = fetchData("$baseUrl/provinces/$code/communes");
        $communes = null;
        if (isset($communesData['data']) && is_array($communesData['data'])) {
            $communes = $communesData['data'];
        } elseif (isset($communesData['communes']) && is_array($communesData['communes'])) {
            $communes = $communesData['communes'];
        } elseif (is_array($communesData)) {
            if (array_keys($communesData) === range(0, count($communesData) - 1)) {
                $communes = $communesData;
            }
        }

        if ($communes) {
            file_put_contents("$communesPath/$code.json", json_encode($communes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            echo "Failed to download communes for $name (Response keys: " . implode(', ', array_keys($communesData ?? [])) . ")\n";
        }
        
        // Be nice to the API
        usleep(50000); // 50ms
    }
    echo "Done!\n";
} else {
    echo "Failed to fetch provinces or invalid format.\n";
    var_dump($provincesData);
}
