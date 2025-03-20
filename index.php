<?php

// Start session
session_start();

// Define base path (assuming index.php is in the project root)
define('BASE_PATH', __DIR__ . '/');

// Include configuration
require_once BASE_PATH . 'app/config/config.php';

// Include helper functions
require_once BASE_PATH . 'app/helpers/functions.php';

// Very basic routing
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$request_uri = trim($request_uri, '/');

// Define routes
$routes = [
    '' => ['controller' => 'LoginController', 'method' => 'index', 'auth' => false],
    'login' => ['controller' => 'LoginController', 'method' => 'index', 'auth' => false],
    'register' => ['controller' => 'RegisterController', 'method' => 'index', 'auth' => false],
    'do_register' => ['controller' => 'RegisterController', 'method' => 'register', 'auth' => false],
    'do_login' => ['controller' => 'LoginController', 'method' => 'login', 'auth' => false],
    'logout' => ['controller' => 'LoginController', 'method' => 'logout', 'auth' => true],
    'home' => ['controller' => 'HomeController', 'method' => 'index', 'auth' => true], // Added HomeController
];

// Route handling
if (array_key_exists($request_uri, $routes)) {
    $route = $routes[$request_uri];

    // Authentication check
    if ($route['auth'] && !isset($_SESSION['user_id'])) {
        redirect('login');
    } elseif (!$route['auth'] && isset($_SESSION['user_id'])) {
        redirect('home');
    }

    // Load controller and call method
    require_once BASE_PATH . 'app/controllers/' . $route['controller'] . '.php';
    $controller = new $route['controller']();
    $controller->{$route['method']}();

} else {
    // Handle 404 (Not Found)
    header('HTTP/1.0 404 Not Found');
    echo "404 Not Found";
}

?>