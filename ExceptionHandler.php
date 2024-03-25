<?php
function myExceptionHandler($exception) {
    http_response_code(500); // Set a generic server error response code.

    // Detailed exception data for logging.
    $debugData = [
        'type' => get_class($exception),
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'stackTrace' => $exception->getTraceAsString(),
    ];

    // Log the detailed error information as a JSON string for better readability and structure.
    error_log(json_encode($debugData));

    // Prepare a user-friendly error message for the client.
    $response = [
        'success' => false,
        'error' => 'An error occurred. Please try again later.'
    ];

    // Send a JSON response with the user-friendly error message.
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Don't forget to register your exception handler to make it active.
set_exception_handler('myExceptionHandler');
