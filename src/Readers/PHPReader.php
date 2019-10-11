<?php

namespace HughCube\IpDb\Readers;

class PHPReader extends Reader
{
    private $data;

    /**
     * Reader constructor.
     * @param string $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->init();
    }

    /**
     * @inheritDoc
     */
    protected function computeFileSize()
    {
        return strlen($this->data);
    }

    /**
     * @inheritDoc
     */
    protected function read($offset, $length)
    {
        return substr($this->data, $offset, $length);
    }
}
