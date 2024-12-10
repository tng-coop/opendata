<?php
use PHPUnit\Framework\TestCase;

class GotoTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset SESSION and COOKIE before each test
        $_SESSION = [];
        $_COOKIE = [];
    }

    public function testPageWithoutSessionOrCookieShowsGenerateForm()
    {
        // No cookie, no session set
        ob_start();
        include __DIR__ . '/goto.php';
        $output = ob_get_clean();

        // We expect the form for generating a UUID to appear
        $this->assertStringContainsString('Generate UUID', $output);
        $this->assertStringContainsString('Go to Above ID', $output);
        $this->assertStringNotContainsString('Go to My ID', $output);
    }

    public function testPageWithSessionUuidShowsMyIDForm()
    {
        $_SESSION['currentDataUuid'] = 'some-uuid';

        ob_start();
        include __DIR__ . '/goto.php';
        $output = ob_get_clean();

        // We now expect the "Go to My ID" form to appear
        $this->assertStringContainsString('Go to My ID', $output);
        $this->assertStringContainsString('Forget My ID', $output);
        $this->assertStringNotContainsString('Generate UUID', $output);
    }

    public function testPageUsesCookieWhenNoSessionSet()
    {
        $_COOKIE['persistent_uuid'] = 'cookie-uuid';

        ob_start();
        include __DIR__ . '/goto.php';
        $output = ob_get_clean();

        // Since cookie is present and no session was set, it should now show the My ID form
        $this->assertStringContainsString('Go to My ID', $output);
        $this->assertStringContainsString('cookie-uuid', $output);
    }
}
