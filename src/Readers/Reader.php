<?php

namespace HughCube\IpDb\Readers;

use HughCube\IpDb\Helpers\Ip;

abstract class Reader
{
    const IPV4 = 1;
    const IPV6 = 2;

    /**
     * @var int ipDB文件大小
     */
    protected $fileSize;

    protected $nodeCount = 0;
    protected $nodeOffset = 0;

    /**
     * @var array
     */
    protected $meta;

    /**
     * 计算文件大小.
     *
     * @return int
     */
    abstract protected function computeFileSize();

    /**
     * 读取文件内容.
     *
     * @param int $offset 指针偏移
     * @param int $length 读取长度
     *
     * @return string|false
     */
    abstract protected function read($offset, $length);

    /**
     * 是否支持IP V6.
     *
     * @return bool
     */
    public function isSupportV6()
    {
        return ($this->meta['ip_version'] & static::IPV6) === static::IPV6;
    }

    /**
     * 是否支持IP V4.
     *
     * @return bool
     */
    public function isSupportV4()
    {
        return ($this->meta['ip_version'] & static::IPV4) === static::IPV4;
    }

    /**
     * 是否支持指定语言
     *
     * @param string $language
     *
     * @return bool
     */
    public function isSupportLanguage($language)
    {
        return in_array($language, $this->getSupportLanguages(), true);
    }

    /**
     * 支持的语言
     *
     * @return array
     */
    public function getSupportLanguages()
    {
        if (isset($this->meta['languages']) && is_array($this->meta['languages'])) {
            return array_keys($this->meta['languages']);
        }

        return [];
    }

    /**
     * @return int UTC Timestamp
     */
    public function getBuildTime()
    {
        return $this->meta['build'];
    }

    /**
     * 获取mete数据.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->fileSize = $this->computeFileSize();
        if ($this->fileSize === false) {
            throw new \UnexpectedValueException('Error determining the size of data.');
        }

        $metaLength = unpack('N', $this->read(0, 4))[1];
        $text = $this->read(4, $metaLength);

        $this->meta = json_decode($text, true);
        if (isset($this->meta['fields']) === false || isset($this->meta['languages']) === false) {
            throw new \Exception('IP Database metadata error.');
        }

        $fileSize = 4 + $metaLength + $this->meta['total_size'];
        if ($fileSize != $this->fileSize) {
            throw  new \Exception('IP Database size error.');
        }

        $this->nodeCount = $this->meta['node_count'];
        $this->nodeOffset = 4 + $metaLength;
    }

    /**
     * 查找ip的信息.
     *
     * @param string $ip
     * @param string $language
     *
     * @return array|null
     */
    public function find($ip, $language)
    {
        if (!$this->isSupportLanguage($language)) {
            throw new \InvalidArgumentException("language : {$language} not support.");
        }

        if (!Ip::isIp($ip)) {
            throw new \InvalidArgumentException("The value \"$ip\" is not a valid IP address.");
        }

        if (Ip::isIp4($ip) && !$this->isSupportV4()) {
            throw new \InvalidArgumentException('The Database not support IPv4 address.');
        } elseif (Ip::isIp6($ip) && !$this->isSupportV6()) {
            throw new \InvalidArgumentException('The Database not support IPv6 address.');
        }

        try {
            $node = $this->findNode($ip);

            if ($node > 0) {
                $data = $this->resolve($node);
                $values = explode("\t", $data);

                return array_slice($values, $this->meta['languages'][$language], count($this->meta['fields']));
            }
        } catch (\Throwable $exception) {
        }
    }

    /**
     * 查找ip的信息, 带上字段.
     *
     * @param string $ip
     * @param string $language
     *
     * @return array|false|null
     */
    public function findMap($ip, $language)
    {
        $array = $this->find($ip, $language);
        if (null === $array) {
            return;
        }

        return array_combine($this->meta['fields'], $array);
    }

    /**
     * @var int
     */
    private $v4offset = 0;

    /**
     * @var array
     */
    private $v6offsetCache = [];

    /**
     *查找Ip的节点.
     *
     * @param $ip
     *
     * @throws \Exception
     *
     * @return int
     */
    private function findNode($ip)
    {
        $binary = inet_pton($ip);
        $bitCount = strlen($binary) * 8; // 32 | 128
        $key = substr($binary, 0, 2);
        $node = 0;
        $index = 0;
        if ($bitCount === 32) {
            if ($this->v4offset === 0) {
                for ($i = 0; $i < 96 && $node < $this->nodeCount; $i++) {
                    $idx = ($i >= 80) ? 1 : 0;
                    $node = $this->readNode($node, $idx);
                    if ($node > $this->nodeCount) {
                        return 0;
                    }
                }
                $this->v4offset = $node;
            } else {
                $node = $this->v4offset;
            }
        } else {
            if (isset($this->v6offsetCache[$key])) {
                $index = 16;
                $node = $this->v6offsetCache[$key];
            }
        }

        for ($i = $index; $i < $bitCount; $i++) {
            if ($node >= $this->nodeCount) {
                break;
            }

            $node = $this->readNode($node, 1 & ((0xFF & ord($binary[$i >> 3])) >> 7 - ($i % 8)));

            if ($i == 15) {
                $this->v6offsetCache[$key] = $node;
            }
        }

        if ($node === $this->nodeCount) {
            return 0;
        } elseif ($node > $this->nodeCount) {
            return $node;
        }

        throw new \Exception('find node failed.');
    }

    /**
     * @param $node
     * @param $index
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function readNode($node, $index)
    {
        return unpack('N', $this->readNodeData(($node * 8 + $index * 4), 4))[1];
    }

    /**
     * @param $node
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function resolve($node)
    {
        $resolved = $node - $this->nodeCount + $this->nodeCount * 8;
        if ($resolved >= $this->fileSize) {
            return;
        }

        $bytes = $this->readNodeData($resolved, 2);
        $size = unpack('N', str_pad($bytes, 4, "\x00", STR_PAD_LEFT))[1];

        $resolved += 2;

        return $this->readNodeData($resolved, $size);
    }

    /**
     * 读取节点数据.
     *
     * @param int $offset
     * @param int $length
     *
     * @throws \Exception
     *
     * @return string
     */
    private function readNodeData($offset, $length)
    {
        if (0 >= $length) {
            return '';
        }

        $value = $this->read(($offset + $this->nodeOffset), $length);
        if (strlen($value) === $length) {
            return $value;
        }

        throw new \Exception('The Database file read bad data.');
    }

    /**
     * 回收资源.
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    public function __destruct()
    {
        $this->close();
    }
}
