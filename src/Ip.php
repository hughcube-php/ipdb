<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/4/10
 * Time: 00:45.
 */

namespace HughCube\IpDb;

use Exception;
use HughCube\IpDb\Helpers\Ip as Helper;
use HughCube\IpDb\Proxies\City\Proxy;
use HughCube\IpDb\Proxies\Info;

class Ip
{
    protected static $proxy = null;

    /**
     * @throws Exception
     */
    protected static function getProxy(): Proxy
    {
        if (!static::$proxy instanceof Proxy) {
            static::$proxy = new Proxy();
        }

        return static::$proxy;
    }

    public static function setProxy(Proxy $proxy)
    {
        static::$proxy = $proxy;
    }

    /**
     * @throws
     *
     * @return null|Info
     * @phpstan-ignore-next-line
     */
    public static function find(string $ip, string $language = null)
    {
        $language = ($language ?: static::getDefaultLanguage());

        return static::getProxy()->findInfo($ip, $language);
    }

    public static function getDefaultLanguage()
    {
        return static::languages()[0] ?? null;
    }

    /**
     * @throws
     * @phpstan-ignore-next-line
     */
    public static function isSupportV4(): bool
    {
        return static::getProxy()->isSupportV4();
    }

    /**
     * @throws
     * @phpstan-ignore-next-line
     */
    public static function isSupportV6(): bool
    {
        return static::getProxy()->isSupportV6();
    }

    /**
     * @throws
     * @phpstan-ignore-next-line
     */
    public static function isSupportLanguage(string $language): bool
    {
        return static::getProxy()->isSupportLanguage($language);
    }

    /**
     * @throws
     * @phpstan-ignore-next-line
     */
    public static function languages(): array
    {
        return static::getProxy()->getSupportLanguages();
    }

    public static function isIp4(string $ip): bool
    {
        return Helper::isIp4($ip);
    }

    public static function isIp6(string $ip): bool
    {
        return Helper::isIp6($ip);
    }

    public static function isIp(string $ip): bool
    {
        return Helper::isIp($ip);
    }
}
