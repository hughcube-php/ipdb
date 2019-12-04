<?php

namespace HughCube\IpDb\Readers;

class PHPReader extends Reader
{
    private $data;

    /**
     * Reader constructor.
     *
     * @param string $data
     *
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->init();
    }

    /**
     * {@inheritdoc}
     */
    protected function computeFileSize()
    {
        return strlen($this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function read($offset, $length)
    {
        return substr($this->data, $offset, $length);
    }
}
