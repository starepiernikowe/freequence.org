<?php
require_once BASE_PATH . 'app/models/Entry.php';

class HomeController {
    public function index($view_data = []) { //Added $view_data = []
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login'); // Redirect to login page if not logged in
        }

        $entryModel = new Entry();
        $entries = $entryModel->getEntriesByUserId($_SESSION['user_id']); // Get entries for the logged-in user

        // Pass the $entries array to the view
        $view_data['entries'] = $entries; // Pass $entries to the view data.
        require_once BASE_PATH . 'app/views/home.php';
    }
}