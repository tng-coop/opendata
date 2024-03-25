<?php
function myExceptionHandler($exception)
{
    http_response_code(500); // Set a generic server error response code.

  // Preprocess the stack trace to improve readability
  $stackTrace = explode("\n", $exception->getTraceAsString());
  $stackTrace = array_map('trim', $stackTrace); // Trim each line

  // Detailed exception data for logging.
  $debugData = [
      'type' => get_class($exception),
      'message' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'stackTrace' => $stackTrace, // Use the preprocessed stack trace
  ];

  // Log the detailed error information as a JSON string for better readability and structure.
  error_log(json_encode($debugData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
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
