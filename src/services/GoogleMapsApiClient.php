<?php
class GoogleMapsApiClient {
    private $apiKey;
    private $cacheTTL = 3600;
    private $cacheFolder;

    public function __construct() {
        $this->apiKey = getenv('GOOGLE_MAPS_API_KEY') ?: '';
        $this->cacheFolder = __DIR__ . "/../cache/";
        if (!is_dir($this->cacheFolder)) mkdir($this->cacheFolder, 0755, true);
    }

    public function geocode($alamat) {
        $kunci = md5($alamat);
        $cacheFile = $this->cacheFolder . $kunci . ".json";
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $this->cacheTTL)) {
            return json_decode(file_get_contents($cacheFile), true);
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($alamat) . "&key=" . $this->apiKey;
        $resp = @file_get_contents($url);
        if ($resp === false) return null;
        file_put_contents($cacheFile, $resp);
        return json_decode($resp, true);
    }

    public function reverseGeocode($lat, $lng) {
        $key = md5($lat . ',' . $lng);
        $cacheFile = $this->cacheFolder . $key . ".json";
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $this->cacheTTL)) {
            return json_decode(file_get_contents($cacheFile), true);
        }
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$this->apiKey}";
        $resp = @file_get_contents($url);
        if ($resp === false) return null;
        file_put_contents($cacheFile, $resp);
        return json_decode($resp, true);
    }
}
