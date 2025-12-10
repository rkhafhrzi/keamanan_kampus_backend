<?php

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once dirname(__DIR__) . '/vendor/autoload.php';


$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = rtrim($scriptDir, '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];


require_once dirname(__DIR__) . '/src/utils/Respons.php';
require_once dirname(__DIR__) . '/src/utils/JWT.php';
require_once dirname(__DIR__) . '/src/utils/AuthMiddleware.php';

$controllers = [
    'AuthController','KendaraanController','InsidenController','TamuController',
    'RuanganController','QRController','LogController'
];

foreach ($controllers as $controller) {
    require_once dirname(__DIR__) . '/src/controllers/' . $controller . '.php';
}

function route($methodTarget, $pattern, $callback)
{
    global $uri, $method;
    if ($method !== $methodTarget) return;
    $cleanUri = trim($uri, '/');
    if ($cleanUri === trim($pattern, '/')) {
        $callback();
        exit;
    }
}


route('POST', 'auth/register', fn() => (new AuthController())->register());
route('POST', 'auth/login', fn() => (new AuthController())->login());


route('POST', 'kendaraan/tambah', function() {
    $user = AuthMiddleware::auth_protect(); 
    (new KendaraanController())->tambahKendaraan();
});

route('POST', 'insiden/lapor', function() {
    $user = AuthMiddleware::auth_protect();
    (new InsidenController())->laporkanInsiden();
});


route('GET', 'kendaraan/daftar', fn() => (new KendaraanController())->daftar());
route('GET', 'insiden/daftar', fn() => (new InsidenController())->daftar());
route('GET', 'insiden/ambil', fn() => (new InsidenController())->ambil());


route('POST', 'log/catat', fn() => (new LogController())->catatAktivitas());
route('GET', 'log/semua', fn() => (new LogController())->semua());

http_response_code(404);
echo json_encode(["status" => "error", "pesan" => "Endpoint tidak ditemukan"]);
exit;
