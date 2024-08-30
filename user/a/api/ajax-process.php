<?php

use app\controller\ProjectController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include '../../../vendor/autoload.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $slug = $_POST['slug'];
    $status = (int) $_POST['status'];

    $controller = new ProjectController();

    $res = $controller->modifySubTaskStatus($slug, $status);
    header('Content-Type: application/json');
    echo $res;

} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
