<?php
use PHPUnit\Framework\TestCase;

class ErrorDisplayTest extends TestCase
{
    protected function setUp(): void
    {
        // Do not start the session here if it causes conflicts.
        // Let the test functions handle their own sessions in separate processes.
    }

    /**
     * @runInSeparateProcess
     */
    public function testErrorIsDisplayedAndUnset()
    {
        // Start session here, so no previous output can occur.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Arrange
        $_SESSION['error'] = 'Something went wrong';
        
        // Act
        ob_start();
        include __DIR__ . '/error.php'; 
        $output = ob_get_clean();

        // Assert
        $this->assertStringContainsString('<p>Something went wrong</p>', $output);
        $this->assertArrayNotHasKey('error', $_SESSION);
    }

    /**
     * @runInSeparateProcess
     */
    public function testNoErrorKeyProducesNoOutput()
    {
        // Start session here as well
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Arrange
        $this->assertArrayNotHasKey('error', $_SESSION);

        // Act
        ob_start();
        include __DIR__ . '/error.php'; 
        $output = ob_get_clean();

        // Assert
        $this->assertEmpty($output);
    }
}
