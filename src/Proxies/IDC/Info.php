<?php

/**
 * @site https://www.ipip.net
 * @desc Parse IP library in ipdb format
 *
 * @copyright IPIP.net
 */

namespace HughCube\IpDb\Proxies\IDC;

class Info extends \HughCube\IpDb\Proxies\Info
{
    public $country_name = '';
    public $region_name = '';
    public $city_name = '';
    public $owner_domain = '';
    public $isp_domain = '';
    public $idc = '';
}
