<?php

namespace HughCube\IpDb\Proxies;

/**
 * Class Info
 * @package HughCube\IpDb\Proxies
 */
class Info
{
    public function __construct(array $data)
    {
        foreach($data as $field => $value){
            $this->{$field} = $value;
        }
    }
}
