<?php

namespace HughCube\IpDb\Readers;

class IpDbReader extends Reader
{
    /**
     * @var false|resource
     */
    private $file;

    /**
     * @var string|null
     */
    private $database;

    /**
     * Reader constructor.
     * @param $database
     * @throws \Exception
     */
    public function __construct($database)
    {
        if (!is_readable($database)) {
            $message = "The IP Database file \"{$database}\" does not exist or is not readable.";
            throw new \InvalidArgumentException($message);
        }

        $this->file = @fopen($database, 'rb');
        if (!is_resource($this->file)) {
            throw new \InvalidArgumentException("IP Database File opening \"{$database}\".");
        }

        $this->database = $database;

        $this->init();
    }

    /**
     * @return PHPReader
     * @throws \Exception
     */
    public function getPHPReader()
    {
        return new PHPReader($this->read(0, $this->fileSize));
    }

    /**
     * @inheritDoc
     */
    protected function computeFileSize()
    {
        return @filesize($this->database);
    }

    /**
     * @inheritDoc
     */
    protected function read($offset, $length)
    {
        if (0 !== fseek($this->file, $offset)) {
            return false;
        }

        return fread($this->file, $length);
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }
}
