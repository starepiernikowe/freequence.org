<?php
require_once BASE_PATH . 'app/models/Entry.php';
require_once BASE_PATH . 'app/models/Template.php';
require_once BASE_PATH . 'app/models/EntryDetail.php';

class EntryController {

    public function add($view_data = []) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $templateModel = new Template();
        $templates = $templateModel->getAllTemplates();

        $view_data['templates'] = $templates;
        require_once BASE_PATH . 'app/views/entries/add.php';
    }

    public function create($view_data = []) {
         if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entry_name = trim(htmlspecialchars($_POST['entry_name']));
            $template_id = (int) $_POST['template_id'];
            $location_lat = (float) $_POST['location_lat'];
            $location_lng = (float) $_POST['location_lng'];
            $comment = trim(htmlspecialchars($_POST['comment']));
            $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : NULL;
            $status = trim(htmlspecialchars($_POST['status']));
            $expiration_years = (int) $_POST['expiration_date'];

            $errors = [];
            if (empty($entry_name)) {
                $errors[] = 'Entry name is required.';
            }
            if (empty($template_id)) {
                $errors[] = 'Template is required.';
            }
            if (!is_numeric($location_lat) || $location_lat < -90 || $location_lat > 90) {
                $errors[] = 'Invalid latitude.';
            }
            if (!is_numeric($location_lng) || $location_lng < -180 || $location_lng > 180) {
                $errors[] = 'Invalid longitude.';
            }

            $templateModel = new Template();
            $template = $templateModel->getTemplateById($template_id);
            if (!$template || !$template['is_active']) {
                $errors[] = 'Invalid template selected.';
            }

            if ($template['name'] === 'System' && empty($description)) {
                $errors[] = 'Description is required for System template.';
            }

            if (empty($errors)) {
                $expiration_date = date('Y-m-d H:i:s', strtotime("+$expiration_years years"));

                $entryModel = new Entry();
                $entryData = [
                    'user_id' => $_SESSION['user_id'],
                    'template_id' => $template_id,
                    'entry_name' => $entry_name,
                    'creation_date' => date('Y-m-d H:i:s'),
                    'expiration_date' => $expiration_date,
                    'location_lat' => $location_lat,
                    'location_lng' => $location_lng,
                    'comment' => $comment,
                    'description' => $description,
                    'entry_type' => 'single',
                    'status' => $status,
                    'parent_entry_id' => NULL,
                     'is_active' => 1,

                ];

                $newEntryId = $entryModel->createEntry($entryData);

                if ($newEntryId) {
                    // Handle dynamic parameters
                    if (isset($_POST['parameters']) && is_array($_POST['parameters'])) {
                        $entryDetailModel = new EntryDetail();
                        foreach ($_POST['parameters'] as $parameterId => $value) {
                            // Sanitize the value
                            $value = trim(htmlspecialchars($value));

                            // Check if the parameter ID is an integer (for security)
                            $parameterId = (int) $parameterId;
                            // Check if parameter id exist, if yes insert parameter_id,
                            if($parameterId){
                                $parameter = $templateModel->getParameterById($parameterId);
                                    if ($parameter) {
                                        $entryDetailModel->createEntryDetail([
                                            'entry_id' => $newEntryId,
                                            'parameter_id' => $parameterId,
                                            'value' => $value,
                                            'is_option' => 0, // Assuming not an option for now
                                        ]);
                                    } else {
                                        error_log("Invalid parameter ID: $parameterId");
                                    }
                            } else { //if parameter id doesn't exist, it will be custom.
                                $entryDetailModel->createEntryDetail([
                                    'entry_id' => $newEntryId,
                                    'custom_parameter_name' => 'Custom Parameter', // A placeholder name
                                    'value' => $value,
                                    'is_option' => 0, // Or determine if it's an option
                                ]);
                            }
                        }
                    }
                    redirect('home');
                } else {
                    $errors[] = 'Failed to create entry. Database error.';
                     $view_data['errors'] = $errors;
                     $templateModel = new Template();
                     $templates = $templateModel->getAllTemplates();
                     $view_data['templates'] = $templates;
                    require_once BASE_PATH . 'app/views/entries/add.php';

                }
            } else {
                $view_data['errors'] = $errors;
                $templateModel = new Template();
                $templates = $templateModel->getAllTemplates();
                $view_data['templates'] = $templates;

                require_once BASE_PATH . 'app/views/entries/add.php';
            }
        }
    }
    public function view($view_data = []) {
        // Authorization check
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        // Get entry ID from the URL (query parameter)
        $entryId = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$entryId) {
            // Handle missing or invalid entry ID (e.g., show an error, redirect)
            echo "Invalid entry ID."; // Replace with a proper error message/redirection
            exit;
        }

        $entryModel = new Entry();
        $entry = $entryModel->getEntryById($entryId);
        //If entry do not exist, or do not belong to user, exit.
        if (!$entry || $entry['user_id'] !== $_SESSION['user_id']) {
             echo "Entry not found or access denied.";
             exit;
        }
        $view_data['entry'] = $entry;
        require_once BASE_PATH . 'app/views/entries/view.php';
    }
}