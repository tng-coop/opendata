<?php
function customErrorLogger($data)
{
    $logFilePath = 'custom_error.log'; // Specify the path to your custom log file

    // Format the data as a JSON string with pretty print for better readability
    $formattedData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    // Optionally, you can format the date and include it in the log message
    $date = new DateTime();
    $formattedMessage = sprintf("[%s] %s\n", $date->format('Y-m-d H:i:s'), $formattedData);

    // Append the formatted message to the log file
    file_put_contents($logFilePath, $formattedMessage, FILE_APPEND);
}


// Example usage within your exception handler
function myExceptionHandler($exception)
{
    http_response_code(500); // Set a generic server error response code.

    $debugData = [
        'type' => get_class($exception),
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'stackTrace' => explode("\n", $exception->getTraceAsString()), // Convert stack trace to array for readability
    ];

    // Use the custom logger instead of error_log
    customErrorLogger($debugData);

    // Prepare a user-friendly error message for the response.
    $response = [
        'success' => false,
        'error' => 'An error occurred. Please try again later.'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Don't forget to register your custom exception handler
set_exception_handler('myExceptionHandler');
