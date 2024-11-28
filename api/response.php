<?php
function sendResponse($statusCode, $data) {
    $statusMessages = [
        200 => 'OK', 
        201 => 'Created', 
        400 => 'Bad Request', 
        404 => 'Not Found', 
        500 => 'Internal Server Error'];
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $statusCode,
        'message' => $statusMessages[$statusCode] ?? 'Unknown Status',
        'data' => $data
    ]);
    exit; 
}
?>
