<?php

namespace HughCube\IpDb\Tests;

use Exception;
use HughCube\IpDb\Exceptions\ExceptionInterface;
use HughCube\IpDb\Exceptions\InvalidArgumentException;
use HughCube\IpDb\Exceptions\InvalidIpException;
use HughCube\IpDb\Exceptions\NotSupportLanguageException;
use HughCube\IpDb\Proxies\Proxy;
use PHPUnit\Framework\TestCase;

class ProxyTest extends TestCase
{
    /**
     * BaseStation.
     *
     * @throws Exception
     */
    public function testBaseStation()
    {
        $proxy = new \HughCube\IpDb\Proxies\BaseStation\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\BaseStation\Info::class);
    }

    /**
     * City.
     *
     * @throws Exception
     */
    public function testCity()
    {
        $proxy = new \HughCube\IpDb\Proxies\City\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\City\Info::class);
    }

    /**
     * District.
     *
     * @throws Exception
     */
    public function testDistrict()
    {
        $proxy = new \HughCube\IpDb\Proxies\District\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\District\Info::class);
    }

    /**
     * IDC.
     *
     * @throws Exception
     */
    public function testIDC()
    {
        $proxy = new \HughCube\IpDb\Proxies\IDC\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\IDC\Info::class);
    }

    /**
     * @param Proxy $proxy
     * @param $infoClass
     * @param string[] $ips
     */
    protected function runTestProxy(Proxy $proxy, $infoClass, array $ips = ['8.8.8.8', '183.17.230.50'])
    {
        foreach ($ips as $ip) {
            foreach ($proxy->getReader()->getSupportLanguages() as $language) {
                $result = $proxy->find($ip, $language);
                $this->assertArrayHasKey(0, $result);
                $this->assertArrayHasKey(1, $result);
                $this->assertArrayHasKey(2, $result);

                $result = $proxy->findMap($ip, $language);
                $this->assertArrayHasKey('country_name', $result);
                $this->assertArrayHasKey('region_name', $result);
                $this->assertArrayHasKey('city_name', $result);

                $result = $proxy->findInfo($ip, $language);
                $this->assertInstanceOf($infoClass, $result);
                $this->assertNotEmpty($result->country_name);
            }

            $exception = null;

            try {
                $proxy->find($ip, time());
            } catch (\Throwable $exception) {
            }
            $this->assertInstanceOf(NotSupportLanguageException::class, $exception);
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(ExceptionInterface::class, $exception);

            $exception = null;

            try {
                $proxy->findMap($ip, time());
            } catch (\Throwable $exception) {
            }
            $this->assertInstanceOf(NotSupportLanguageException::class, $exception);
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(ExceptionInterface::class, $exception);

            $exception = null;

            try {
                $proxy->findInfo($ip, time());
            } catch (\Throwable $exception) {
            }
            $this->assertInstanceOf(NotSupportLanguageException::class, $exception);
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(ExceptionInterface::class, $exception);
        }

        $invalidIp = time();

        $exception = null;

        try {
            $proxy->find($invalidIp, $language);
        } catch (\Throwable $exception) {
        }
        $this->assertInstanceOf(InvalidIpException::class, $exception);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);

        $exception = null;

        try {
            $proxy->findMap($invalidIp, $language);
        } catch (\Throwable $exception) {
        }
        $this->assertInstanceOf(InvalidIpException::class, $exception);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);

        $exception = null;

        try {
            $proxy->findInfo($invalidIp, $language);
        } catch (\Throwable $exception) {
        }
        $this->assertInstanceOf(InvalidIpException::class, $exception);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
