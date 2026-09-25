<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\SupplierController;
use App\Models\Supplier;
use App\Models\User;

$services = require dirname(__DIR__) . '/src/bootstrap.php';
$database = $services['database'];
$view = $services['view'];
$auth = $services['auth'];

$authController = new AuthController($view, $auth, new User($database));
$supplierModel = new Supplier($database);
$dashboardController = new DashboardController($view, $auth, $supplierModel);
$supplierController = new SupplierController($view, $auth, $supplierModel);

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';

$routes = [
    'GET /' => static function () use ($auth): never {
        header('Location: ' . ($auth->check() ? '/dashboard' : '/login'));
        exit;
    },
    'GET /register' => [$authController, 'showRegister'],
    'POST /register' => [$authController, 'register'],
    'GET /login' => [$authController, 'showLogin'],
    'POST /login' => [$authController, 'login'],
    'POST /logout' => [$authController, 'logout'],
    'GET /dashboard' => [$dashboardController, 'index'],
    'GET /suppliers' => [$supplierController, 'index'],
    'GET /suppliers/create' => [$supplierController, 'create'],
    'POST /suppliers/create' => [$supplierController, 'store'],
];

$routeKey = $method . ' ' . $path;
if (isset($routes[$routeKey])) {
    call_user_func($routes[$routeKey]);
    exit;
}

if (preg_match('#^/suppliers/edit/(\d+)$#', $path, $matches)) {
    if ($method === 'GET') {
        $supplierController->edit((int) $matches[1]);
        exit;
    }
    if ($method === 'POST') {
        $supplierController->update((int) $matches[1]);
        exit;
    }

    http_response_code(405);
    header('Allow: GET, POST');
    exit('Метод не поддерживается.');
}

if ($method === 'DELETE' && preg_match('#^/api/suppliers/(\d+)$#', $path, $matches)) {
    $supplierController->destroy((int) $matches[1]);
    exit;
}

http_response_code(404);
$view->render('errors/404', ['title' => 'Страница не найдена', 'auth' => $auth]);
