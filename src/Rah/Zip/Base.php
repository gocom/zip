<?php

/*
 * Rah/Zip - Wrapper for ZipArchive
 * https://github.com/gocom/zip
 *
 * Copyright (C) 2013 Jukka Svahn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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
     * @param string $filename The filename
     * @param int    $flags    The flags
     */

    protected function open($filename, $flags = ZIPARCHIVE::OVERWRITE)
    {
        if ($this->zip->open($filename, $flags) !== true)
        {
            throw new Exception('Unable to open: ' . $this->config->file);
        }
    }

    /**
     * Closes a file.
     */

    protected function close()
    {
        @$this->zip->close();
    }

    /**
     * Gets a path relative within the given directory.
     *
     * @param  string $directory The base directory
     * @param  string $file      The file
     * @return string The path
     */

    protected function relativePath($directory, $file)
    {
        $directory = dirname($directory);
        $directory = $this->normalizePath($directory);
        $file = $this->normalizePath($file);

        if (strpos($file.'/', $directory.'/') === 0)
        {
            return substr($file, strlen($directory) + 1);
        }

        return $file;
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