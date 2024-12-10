<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/ExceptionHandler.php';

class ExceptionHandlerTest extends TestCase
{
    private $testLogPath;

    protected function setUp(): void
    {
        // Create a temporary log file path for testing
        $this->testLogPath = sys_get_temp_dir() . '/test_custom_error.log';
        if (file_exists($this->testLogPath)) {
            unlink($this->testLogPath);
        }

        // Restore any previous handlers to avoid conflicts.
        restore_error_handler();
        restore_exception_handler();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogPath)) {
            unlink($this->testLogPath);
        }

        // Restore any handlers changed by tests
        restore_error_handler();
        restore_exception_handler();
    }

    public function testCustomErrorLoggerWritesToFile()
    {
        $data = [
            'type' => 'Error',
            'code' => E_WARNING,
            'message' => 'Test warning message',
            'file' => '/path/to/file.php',
            'line' => 123,
        ];

        // Call the logger with a custom path
        customErrorLogger($data, $this->testLogPath);

        $this->assertFileExists($this->testLogPath, 'Log file was not created.');
        $contents = file_get_contents($this->testLogPath);
        $this->assertStringContainsString('Test warning message', $contents, 'Log file did not contain the expected message.');
        $this->assertStringContainsString('"type": "Error"', $contents, 'Log file JSON structure is not correct.');
    }

    public function testHandleErrorLogsErrorData()
    {
        $errno   = E_USER_NOTICE;
        $errstr  = 'Test handleError message';
        $errfile = __FILE__;
        $errline = __LINE__;

        // Manually call handleError with the custom log path
        handleError($errno, $errstr, $errfile, $errline, $this->testLogPath);

        $this->assertFileExists($this->testLogPath, 'Log file was not created by handleError.');
        $contents = file_get_contents($this->testLogPath);
        $this->assertStringContainsString('Test handleError message', $contents, 'Log did not contain the triggered error message.');
        $this->assertStringContainsString('"type": "Error"', $contents, 'Log file did not contain the expected "type": "Error".');
    }

    public function testMyExceptionHandlerOutputsJsonAndLogs()
    {
        // Capture output
        ob_start();

        // Create and handle an exception with a custom log path
        $exception = new Exception("Test exception handling");
        myExceptionHandler($exception, $this->testLogPath);

        $output = ob_get_clean();

        // Check the JSON structure in the output
        $this->assertStringContainsString('"success": false', $output, 'Output JSON does not have the success flag set to false.');
        $this->assertStringContainsString('"error": "An error occurred."', $output, 'Output JSON does not contain the expected error message.');
        $this->assertStringContainsString('Test exception handling', $output, 'Exception details are not printed in output.');

        // Check if it was logged
        $this->assertFileExists($this->testLogPath, 'Log file was not created by exception handler.');
        $contents = file_get_contents($this->testLogPath);
        $this->assertStringContainsString('Test exception handling', $contents, 'Exception message not logged as expected.');
    }
}
