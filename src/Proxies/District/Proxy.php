<?php

namespace HughCube\IpDb\Proxies\District;

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
