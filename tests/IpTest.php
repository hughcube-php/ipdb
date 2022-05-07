<?php

namespace HughCube\IpDb\Tests;

use Exception;
use HughCube\IpDb\Exceptions\ExceptionInterface;
use HughCube\IpDb\Exceptions\InvalidArgumentException;
use HughCube\IpDb\Exceptions\InvalidIpException;
use HughCube\IpDb\Exceptions\NotSupportLanguageException;
use HughCube\IpDb\Ip;
use HughCube\IpDb\Proxies\Info;
use HughCube\IpDb\Proxies\Proxy;
use PHPUnit\Framework\TestCase;
use Throwable;

class IpTest extends TestCase
{
    /**
     * BaseStation.
     *
     * @throws Exception
     */
    public function testFind()
    {
        $ip = '8.8.8.8';
        $info = Ip::find($ip);
        $this->assertInstanceOf(Info::class, $info);
        $this->assertSame($info->ip, $ip);

        try {
            Ip::find(time());
        } catch (Throwable $exception) {
        }
        $this->assertInstanceOf(Throwable::class, ($exception ?? null));
    }

    public function testGetDefaultLanguage()
    {
        $this->assertIsString(Ip::getDefaultLanguage());
    }

    public function testIsSupportV4()
    {
        $this->assertIsBool(Ip::isSupportV4());
    }

    public function testIsSupportV6()
    {
        $this->assertIsBool(Ip::isSupportV6());
    }

    public function testIsSupportLanguage()
    {
        $this->assertIsBool(Ip::isSupportLanguage('test'));
    }

    public function testLanguages()
    {
        $this->assertIsArray(Ip::languages());
    }
}
