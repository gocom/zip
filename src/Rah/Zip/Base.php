<?php

/**
 * Base class.
 */

abstract class Rah_Zip_Base
{
    /**
     * The config.
     *
     * @var Rah_Zip_Config
     */

    protected $config;

    /**
     * An instance of ZipArchive.
     *
     * @var ZipArchive
     */

    protected $zip;

    /**
     * Path to a temporary file.
     *
     * @var string
     */

    protected $temp;

    /**
     * Constructor.
     *
     * @param Rah_Zip_Config
     */

    public function __construct($config)
    {
        if (!class_exists('ZipArchive'))
        {
            throw new Exception('ZipArchive is not installed.');
        }

        $this->config = $config;
        $this->zip = new ZipArchive();
        $this->init();
    }

    /**
     * Destructor.
     */

    public function __destruct()
    {
        $this->close();
        $this->clean();
    }

    /**
     * Normalizes a filename.
     *
     * @param  string $path
     * @return string
     */

    protected function normalizePath($path)
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    /**
     * Gets a path to a temporary file acting as a buffer.
     */

    protected function tmpFile()
    {
        if (($this->temp = tempnam($this->config->tmp, 'Rah_Zip')) === false)
        {
            throw new Exception('Unable to create a temporary file.');
        }

        if (rename($this->temp, $this->temp . '.zip') === false || unlink($this->temp . '.zip') === false)
        {
            throw new Exception('Unable to create a temporary file.');
        }

        $this->temp .= '.zip';
    }

    /**
     * Moves the temprary file to the final location.
     */

    protected function move()
    {
        if (@rename($this->temp, $this->config->file))
        {
            return true;
        }

        if (@copy($this->temp, $this->config->file) && unlink($this->temp))
        {
            return true;
        }

        throw new Exception('Unable to move the temporary file.');
    }

    /**
     * Cleans temporary trash files.
     */

    protected function clean()
    {
        if (file_exists($this->temp))
        {
            unlink($this->temp);
        }
    }

    /**
     * Opens a file.
     *
     * @param int $flags
     */

    protected function open($flags = ZIPARCHIVE::OVERWRITE)
    {
        if ($this->zip->open($this->temp, $flags) !== true)
        {
            throw new Exception('Unable to open: ' . $this->config->file);
        }
    }

    /**
     * Closes a file.
     *
     * @todo Verify that something is open
     */

    protected function close()
    {
        if (@$this->zip->close() === false)
        {
            throw new Exception('Unable to close: ' . $this->config->file);
        }
    }

    /**
     * Returns a path to the archive.
     *
     * @return string
     */

    public function __toString()
    {
        return (string) $this->config->file;
    }
}