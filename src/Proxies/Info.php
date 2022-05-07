<?php

namespace HughCube\IpDb\Proxies;

/**
 * Class Info.
 */
class Info
{
    public $ip;
    public $country_name;
    public $region_name;
    public $city_name;

    public function __construct($ip, array $data)
    {
        $this->ip = $ip;
        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }
    }
}
