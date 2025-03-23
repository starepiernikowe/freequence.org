<?php
require_once BASE_PATH . 'app/models/Template.php';

class TemplateController {

    public function getParameters($view_data = []) {
        // Check for logged-in user (primary check)
        if (!isset($_SESSION['user_id'])) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $templateId = (int) $_GET['template_id'];
        $templateModel = new Template();
        $parameters = $templateModel->getParametersByTemplateId($templateId);

        header('Content-Type: application/json');
        echo json_encode($parameters);
        exit;
    }
}