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
    '' => ['controller' => 'LoginController', 'method' => 'index', 'auth' => false, 'title' => 'Login'],
    'login' => ['controller' => 'LoginController', 'method' => 'index', 'auth' => false, 'title' => 'Login'],
    'register' => ['controller' => 'RegisterController', 'method' => 'index', 'auth' => false, 'title' => 'Register'],
    'do_register' => ['controller' => 'RegisterController', 'method' => 'register', 'auth' => false, 'title' => 'Register'],
    'do_login' => ['controller' => 'LoginController', 'method' => 'login', 'auth' => false, 'title' => 'Login'],
    'logout' => ['controller' => 'LoginController', 'method' => 'logout', 'auth' => true, 'title' => 'Logout'],
    'home' => ['controller' => 'HomeController', 'method' => 'index', 'auth' => true, 'title' => 'Home'],
    'add_entry' => ['controller' => 'EntryController', 'method' => 'add', 'auth' => true, 'title' => 'Add Entry'],
    'entry/create' => ['controller' => 'EntryController', 'method' => 'create', 'auth' => true, 'title' => 'Add Entry'], // For form submission
    'templates/parameters' => ['controller' => 'TemplateController', 'method' => 'getParameters', 'auth' => true, 'title' => 'Get Template Parameters'],
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

    // Prepare data array for the view
    $view_data = [
        'page_title' => $route['title'],
        // Add other data here as needed in the future
    ];

    $controller->{$route['method']}($view_data); // Pass $view_data

} else {
    // Handle 404 (Not Found)
    header('HTTP/1.0 404 Not Found');
    echo "404 Not Found";
}
?>