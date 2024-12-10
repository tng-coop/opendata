<?php

function customErrorLogger($data, $logFilePath = __DIR__ . '/../custom_error.log')
{
    // Format the data as a JSON string with pretty print for better readability
    $formattedData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    // Optionally, include the date in the log message
    $date = new DateTime();
    $formattedMessage = sprintf("[%s] %s\n", $date->format('Y-m-d H:i:s'), $formattedData);

    // Append the formatted message to the log file
    file_put_contents($logFilePath, $formattedMessage, FILE_APPEND | LOCK_EX);
}

function handleError($errno, $errstr, $errfile, $errline, $logFilePath = __DIR__ . '/../custom_error.log')
{
    $errorData = [
        'type' => 'Error',
        'code' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline,
    ];
    customErrorLogger($errorData, $logFilePath);
}

// Enhanced exception handler
function myExceptionHandler($exception, $logFilePath = __DIR__ . '/../custom_error.log')
{
    http_response_code(500); // Set the HTTP status code to 500

    $debugData = [
        'type' => get_class($exception),
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'stackTrace' => explode("\n", $exception->getTraceAsString()),
    ];

    // Log the error
    customErrorLogger($debugData, $logFilePath);

    // Format the error details as a readable string
    $formattedDetails = sprintf(
        "Exception Type: %s\nMessage: %s\nFile: %s\nLine: %d\n\nStack Trace:\n%s",
        $debugData['type'],
        $debugData['message'],
        $debugData['file'],
        $debugData['line'],
        implode("\n", $debugData['stackTrace'])
    );

    // Prepare the main response (without details)
    $response = [
        'success' => false,
        'error' => 'An error occurred.'
    ];

    header('Content-Type: application/json');

    // Output the main JSON response
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    // Output the details separately as plain text
    echo "\n\nError Details:\n{$formattedDetails}";
    exit;
}

// Register the handlers with their default log file path
set_exception_handler(function($exception) {
    myExceptionHandler($exception);
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    handleError($errno, $errstr, $errfile, $errline);
});
