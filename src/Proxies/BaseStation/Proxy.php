<?php

namespace HughCube\IpDb\Proxies\BaseStation;

/**
 * Class Proxy
 * @package HughCube\IpDb\Proxies\BaseStation
 */
class Proxy extends \HughCube\IpDb\Proxies\Proxy
{
    /**
     * @inheritDoc
     * @return Info
     */
    public function findInfo($ip, $language)
    {
        $map = $this->findMap($ip, $language);

        return null == $map ? null : (new Info($map));
    }
}
