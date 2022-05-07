<?php

namespace HughCube\IpDb\Proxies;

use Exception;
use HughCube\IpDb\Readers\IpDbReader;
use HughCube\IpDb\Readers\Reader;

/**
 * @mixin Reader
 */
abstract class Proxy
{
    /**
     * @var Reader|null
     */
    protected $reader = null;

    /**
     * Proxy constructor.
     *
     * @param  Reader|null  $reader
     *
     * @throws Exception
     */
    public function __construct(Reader $reader = null)
    {
        $reader = null === $reader ? $this->getDefaultReader() : $reader;

        $this->reader = $reader;
    }

    /**
     * 获取默认的 Reader.
     *
     * @return Reader
     * @throws Exception
     */
    protected function getDefaultReader(): Reader
    {
        $reader = new IpDbReader((__DIR__.'/../../data/ipipfree.ipdb'));

        return $reader->getPHPReader();
    }

    /**
     * @return Reader|null
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * 根据ip查找信息.
     *
     * @param  string  $ip  查找的ip
     * @param  string  $language  语言
     *
     * @return array|null
     */
    public function find($ip, $language)
    {
        return $this->getReader()->find($ip, $language);
    }

    /**
     * 根据ip查找信息map.
     *
     * @param  string  $ip  查找的ip
     * @param  string  $language  语言
     *
     * @return array|null
     */
    public function findMap($ip, $language)
    {
        return $this->getReader()->findMap($ip, $language);
    }

    /**
     * 根据ip查找信息对象
     *
     * @param  string  $ip  查找的ip
     * @param  string  $language  语言
     *
     * @return mixed
     */
    abstract public function findInfo($ip, $language);

    /**
     * @param  string  $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments = [])
    {
        return $this->reader->$name(...$arguments);
    }
}
