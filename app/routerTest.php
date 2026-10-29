<?php

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/loadAppConfig.php';

global $capturedHeaders;
$capturedHeaders = [];

function my_header($string) {
    global $capturedHeaders;
    $capturedHeaders[] = $string;
}

class RouterTest extends TestCase
{
    private $originalServer;
    private $originalSession;
    private $originalCookie;
    private $originalMethod;
    private $originalUri;
    private $originalAppConfig;
    private $appConfigMock;

    protected function setUp(): void
    {
        // Start a session before the parent setup.
        session_start();

        parent::setUp();

        $this->originalServer = $_SERVER;
        $this->originalSession = $_SESSION ?? [];
        $this->originalCookie = $_COOKIE ?? [];
        $this->originalMethod = $GLOBALS['method'] ?? null;
        $this->originalUri = $GLOBALS['uri'] ?? null;
        $this->originalAppConfig = $GLOBALS['appConfig'] ?? null;

        global $capturedHeaders;
        $capturedHeaders = []; // reset captured headers

        // Mock global $appConfig fully
        $this->appConfigMock = $this->createMock(AppConfig::class);
        $this->appConfigMock->method('get')->willReturnMap([
            ['url.base', 'http://example.com/'],
            ['url.root', '/app/'],
            ['database.host', 'localhost'],
            ['database.dbname', 'tng'],
            ['database.user', 'yasu'],
            ['database.pass', 'tng123'],
            ['database.port', '5532']
        ]);

        $GLOBALS['appConfig'] = $this->appConfigMock;
        global $appConfig;
        $appConfig = $this->appConfigMock;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->originalServer;
        $_SESSION = $this->originalSession;
        $_COOKIE = $this->originalCookie;

        $GLOBALS['method'] = $this->originalMethod;
        $GLOBALS['uri'] = $this->originalUri;
        $GLOBALS['appConfig'] = $this->originalAppConfig;

        global $appConfig;
        $appConfig = $this->originalAppConfig;

        parent::tearDown();
    }

    /**
     * @runInSeparateProcess
     */
    public function testUmbWithValidUuidRedirectsToUmbPhp()
    {
        $validUuid = Uuid::uuid4()->toString();
        $_SERVER['REQUEST_URI'] = '/app/umb/' . $validUuid;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $GLOBALS['method'] = 'GET';
        $GLOBALS['uri'] = $_SERVER['REQUEST_URI'];
        $_SESSION = [];
        $_COOKIE = [];

        global $appConfig;
        $appConfig = $GLOBALS['appConfig'];

        ob_start();
        include __DIR__ . '/router.php';
        $output = ob_get_clean();

        // Now that session_start() is called, $_SESSION['umb-uuid'] should persist
        $this->assertEquals($validUuid, $_SESSION['umb-uuid']);
        $this->assertTrue($this->headerSentContains('Location: http://example.com/app/umb.php'));
    }

    // ... other tests remain the same, also preceded by session_start() in setUp

    private function headerSentContains($needle)
    {
        global $capturedHeaders;
        foreach ($capturedHeaders as $header) {
            if (stripos($header, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}
