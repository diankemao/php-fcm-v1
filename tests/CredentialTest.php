<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1\tests;

require_once __DIR__ . '/../vendor/autoload.php';

use InvalidArgumentException;
use phpFCMv1\Credentials;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 * @coversNothing
 */
class CredentialTest extends TestCase
{
    public const KEYFILE_PATH = 'service_account.json';

    public const INVALID_KEY_FILE = 'service_account_false.json';

    public function testWithoutKeyFile()
    {
        $this->expectException(InvalidArgumentException::class);
        new Credentials(null);
    }

    public function testWithKeyFile()
    {
        $instance = new Credentials(self::KEYFILE_PATH);
        $this->assertEquals(self::KEYFILE_PATH, $instance->getKeyFilePath());
    }

    public function testOpenKeyFile()
    {
        $body        = file_get_contents(self::KEYFILE_PATH);
        $encodedBody = json_decode($body, true);
        $this->assertNotNull($encodedBody['private_key']);
    }

    public function testAcquireAccessToken()
    {
        $tokenPrefix = 'ya29.';
        $instance    = new Credentials(self::KEYFILE_PATH);
        try {
            $accessToken = $instance->getAccessToken();
        } catch (RuntimeException $exception) {
            echo $exception->getMessage();
        }

        $this->assertStringStartsWith($tokenPrefix, $accessToken);
    }

    public function testInvalidAssertion()
    {
        $this->expectException(RuntimeException::class);

        $instance = new Credentials(self::INVALID_KEY_FILE);
        $instance->getAccessToken();
    }
}
