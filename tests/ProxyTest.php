<?php

namespace HughCube\IpDb\Tests;

use HughCube\IpDb\Proxies\Proxy;
use PHPUnit\Framework\TestCase;

class ProxyTest extends TestCase
{
    /**
     * BaseStation
     *
     * @throws \Exception
     */
    public function testBaseStation()
    {
        $proxy = new \HughCube\IpDb\Proxies\BaseStation\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\BaseStation\Info::class);
    }

    /**
     * City
     *
     * @throws \Exception
     */
    public function testCity()
    {
        $proxy = new \HughCube\IpDb\Proxies\City\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\City\Info::class);
    }

    /**
     * District
     *
     * @throws \Exception
     */
    public function testDistrict()
    {
        $proxy = new \HughCube\IpDb\Proxies\District\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\District\Info::class);
    }

    /**
     * IDC
     *
     * @throws \Exception
     */
    public function testIDC()
    {
        $proxy = new \HughCube\IpDb\Proxies\IDC\Proxy();
        $this->runTestProxy($proxy, \HughCube\IpDb\Proxies\IDC\Info::class);
    }

    /**
     * @param \HughCube\IpDb\Proxies\Proxy $proxy
     */
    protected function runTestProxy(Proxy $proxy, $infoClass, $ips = ['8.8.8.8'])
    {
        $languages = $proxy->getReader()->getSupportLanguages();

        foreach($ips as $ip){
            $result = $proxy->find($ip, $languages[0]);
            $this->assertArrayHasKey(0, $result);
            $this->assertArrayHasKey(1, $result);
            $this->assertArrayHasKey(2, $result);

            $result = $proxy->findMap($ip, $languages[0]);
            $this->assertArrayHasKey('country_name', $result);
            $this->assertArrayHasKey('region_name', $result);
            $this->assertArrayHasKey('city_name', $result);

            $result = $proxy->findInfo($ip, $languages[0]);
            $this->assertInstanceOf($infoClass, $result);
            $this->assertNotEmpty($result->country_name);
        }
    }
}
