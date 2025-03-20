<?php
require_once BASE_PATH . 'app/models/User.php';

class LoginController {

    public function index() {
        // Redirect to home if already logged in
        if (isset($_SESSION['user_id'])) {
            redirect('home');
        }
        require_once BASE_PATH . 'app/views/login.php';
    }

   public function login()
    {
        // Redirect to home if already logged in.
        if (isset($_SESSION['user_id'])) {
            redirect('home');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $username = trim(htmlspecialchars($_POST['username']));
            $password = $_POST['password'];

            // Basic validation
            $errors = [];
            if (empty($username)) {
                $errors[] = 'Username is required.';
            }
            if (empty($password)) {
                $errors[] = 'Password is required.';
            }

            if (empty($errors)) {
                $userModel = new User();
                $user = $userModel->findUserByUsernameOrEmail($username, $username); // Allow login with either username or email

                if ($user && password_verify($password, $user['password'])) {
                    // Login successful, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // Redirect to the home page
                    redirect('home');

                } else {
                    $errors[] = 'Invalid username or password.';
                }
            }
            // Re-display the login form with error messages
            require_once BASE_PATH . 'app/views/login.php';
        } else {
            redirect('login');
        }
    }

     public function logout() {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        redirect('login');
    }
}