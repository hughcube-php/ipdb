<?php

/**
 * @site https://www.ipip.net
 * @desc Parse IP library in ipdb format
 *
 * @copyright IPIP.net
 */

namespace HughCube\IpDb\Proxies\District;

class Info extends \HughCube\IpDb\Proxies\Info
{
    public $country_name = '';
    public $region_name = '';
    public $city_name = '';
    public $district_name = '';
    public $china_admin_code = '';
    public $covering_radius = '';
    public $longitude = '';
    public $latitude = '';
}
