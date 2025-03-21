<?php
require_once BASE_PATH . 'app/models/User.php';

class RegisterController {

  public function index($view_data = []) { // Accept $view_data
        // Redirect to home if already logged in
        if (isset($_SESSION['user_id'])) {
            redirect('home');
        }
        require_once BASE_PATH . 'app/views/register.php';
    }

    public function register($view_data = []) { // Accept $view_data
        // Redirect to home if already logged in
         if (isset($_SESSION['user_id'])) {
            redirect('home');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $username = trim(htmlspecialchars($_POST['username']));
            $email = trim(htmlspecialchars($_POST['email']));
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Basic validation
            $errors = [];
            if (empty($username)) {
                $errors[] = 'Username is required.';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required.';
            }
            if (empty($password)) {
                $errors[] = 'Password is required.';
            }
            if ($password !== $confirm_password) {
                $errors[] = 'Passwords do not match.';
            }

            // If no errors, proceed with registration
            if (empty($errors)) {
                $userModel = new User();
                $existingUser = $userModel->findUserByUsernameOrEmail($username, $email);

                if ($existingUser) {
                    $errors[] = 'Username or email already exists.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $registrationResult = $userModel->registerUser($username, $email, $hashedPassword);

                    if ($registrationResult) {
                        // Registration successful, redirect to login
                        redirect('login');
                    } else {
                        $errors[] = 'Registration failed. Please try again.';
                    }
                }
            }
            $view_data['errors'] = $errors; //Pass errors to the view.

            // If there are errors, re-display the registration form with error messages
            require_once BASE_PATH . 'app/views/register.php';
        } else {
            // If not a POST request, redirect to the registration form
            redirect('register');
        }
    }
}