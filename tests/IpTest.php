<?php

namespace HughCube\IpDb\Tests;

use Exception;
use HughCube\IpDb\Ip;
use HughCube\IpDb\Proxies\Info;
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
        $this->assertTrue(is_string(Ip::getDefaultLanguage()));
    }

    public function testIsSupportV4()
    {
        $this->assertTrue(is_bool(Ip::isSupportV4()));
    }

    public function testIsSupportV6()
    {
        $this->assertTrue(is_bool(Ip::isSupportV6()));
    }

    public function testIsSupportLanguage()
    {
        $this->assertTrue(is_bool(Ip::isSupportLanguage('test')));
    }

    public function testLanguages()
    {
        $this->assertTrue(is_array(Ip::languages()));
    }
}
