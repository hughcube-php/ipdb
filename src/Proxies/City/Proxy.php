<?php

namespace HughCube\IpDb\Proxies\City;

class Proxy extends \HughCube\IpDb\Proxies\Proxy
{
    /**
     * {@inheritdoc}
     *
     * @return Info
     */
    public function findInfo($ip, $language)
    {
        $map = $this->findMap($ip, $language);

        return null == $map ? null : (new Info($map));
    }
}
