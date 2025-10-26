<?php
// Konfigurasi dasar API
$apiKey = "1a3a8858084e48b2b4017ce556697b02";
$baseUrlEverything = "https://newsapi.org/v2/everything";
$baseUrlTopHeadlines = "https://newsapi.org/v2/top-headlines";

/**
 * Fungsi umum untuk melakukan request API menggunakan cURL
 */
function callAPI($url) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        throw new Exception("cURL Error: " . $error);
    }
    return $response;
}

// --------------- TEST CASE 1 ---------------
// Tujuan: Memastikan koneksi API berhasil dan respon 200 OK
try {
    $keyword = "technology";
    $url = "$baseUrlEverything?q=" . urlencode($keyword) . "&apiKey=$apiKey";
    $response = callAPI($url);
    $data = json_decode($response, true);

    if (isset($data['status']) && $data['status'] === "ok") {
        echo "✅ TC_API_001 - Test koneksi API: PASS\n";
    } else {
        echo "❌ TC_API_001 - Test koneksi API: FAIL\n";
    }
} catch (Exception $e) {
    echo "❌ TC_API_001 - Exception: " . $e->getMessage() . "\n";
}


// --------------- TEST CASE 2 ---------------
// Tujuan: Memastikan penanganan error ketika API Key salah
try {
    $invalidKey = "WRONG_KEY";
    $url = "$baseUrlEverything?q=business&apiKey=$invalidKey";
    $response = callAPI($url);
    $data = json_decode($response, true);

    if (isset($data['code']) && $data['code'] === "apiKeyInvalid") {
        echo "✅ TC_API_002 - Test invalid API key: PASS\n";
    } else {
        echo "❌ TC_API_002 - Test invalid API key: FAIL\n";
    }
} catch (Exception $e) {
    echo "✅ TC_API_002 - Exception tertangkap dengan benar: PASS (" . $e->getMessage() . ")\n";
}


// --------------- TEST CASE 3 ---------------
// Tujuan: Memastikan struktur JSON memiliki field wajib (status, totalResults, articles)
try {
    // Ganti endpoint ke top-headlines agar tidak 400 Bad Request
    $category = "sports";
    $url = "$baseUrlTopHeadlines?category=$category&country=us&apiKey=$apiKey";
    $response = callAPI($url);
    $data = json_decode($response, true);

    if (isset($data['status']) && isset($data['totalResults']) && isset($data['articles'])) {
        echo "✅ TC_API_003 - Test struktur JSON: PASS\n";
    } else {
        echo "❌ TC_API_003 - Test struktur JSON: FAIL (field hilang)\n";
    }
} catch (Exception $e) {
    echo "❌ TC_API_003 - Exception: " . $e->getMessage() . "\n";
}
?>
