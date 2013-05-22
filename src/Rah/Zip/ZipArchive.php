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
 * ZipArchive implementation.
 */

class Rah_Zip_ZipArchive
{
    /**
     * The instance.
     *
     * @var ZipArchive
     */

    protected $zip;

    /**
     * The file descriptor limit and a reset point.
     *
     * @var int 
     */

    protected $descriptor = 100;

    /**
     * Number of files processed.
     *
     * @var int
     */

    protected $stackSize = 0;

    /**
     * Currently open file.
     *
     * @var string
     */

    protected $filename;

    /**
     * Whether the archive is open.
     *
     * @var bool
     */

    protected $isOpen = false;

    /**
     * Added filenames are relative to a directory path.
     *
     * @var string
     */

    protected $basepath = '';

    /**
     * Constructor.
     */

    public function __construct()
    {
        if (class_exists('ZipArchive'))
        {
            $this->zip = new ZipArchive();
        }
        else
        {
            throw new Exception('ZipArchive is not installed.');
        }
    }

    /**
     * Destructor.
     */

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Normalizes a filename.
     *
     * Makes sure the created Zip contains
     * valid filenames, and can be extracted.
     *
     * @param  string $path
     * @return string
     */

    protected function normalizePath($path)
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    /**
     * Gets a path relative within the given directory.
     *
     * @param  string $file    The file
     * @return string The path
     */

    protected function relativePath($file)
    {
        $directory = dirname($this->basepath);
        $directory = $this->normalizePath($directory);
        $file = $this->normalizePath($file);

        if (strpos($file.'/', $directory.'/') === 0)
        {
            return substr($file, strlen($directory) + 1);
        }

        return $file;
    }

    /**
     * Opens a file.
     *
     * @param string $filename The filename
     * @param int    $flags    The flags
     */

    public function open($filename, $flags = ZIPARCHIVE::OVERWRITE)
    {
        if ($this->zip->open($filename, $flags) !== true)
        {
            throw new Exception('Unable to open: ' . $filename);
        }
        else
        {
            $this->filename = $filename;
            $this->isOpen = true;
        }
    }

    /**
     * Closes the file.
     */

    public function close()
    {
        if ($this->isOpen === true)
        {
            $this->zip->close();
            $this->isOpen = false;
        }
    }

    /**
     * Adds a file to the archive.
     *
     * @param string $file      The filename
     */

    public function addFile($file)
    {
        $this->resetStack();
        $localname = $this->relativePath($file);

        if ($this->zip->addFile($file, $localname) !== true)
        {
            throw new Exception('Unable add file to the archive.');
        }
    }

    /**
     * Adds an empty directory to the archive.
     *
     * @param string $localname The localname
     */

    public function addEmptyDir($localname)
    {
        $this->resetStack();
        $localname = $this->relativePath($localname);

        if ($this->zip->addEmptyDir($localname) !== true)
        {
            throw new Exception('Unable add directory to the archive.');
        }
    }

    /**
     * Sets the base path.
     *
     * @param string Path to the directory
     */

    public function baseDirectory($directory)
    {
        $this->basepath = $directory;
    }

    /**
     * Extracts an archive.
     *
     * @param string $filename
     */

    public function extractTo($filename)
    {
        if ($this->zip->extractTo($filename) !== true)
        {
            throw new Exception('Unable to extract to: ' . $filename);
        }
    }

    /**
     * Reset the current stack.
     *
     * This prevents ZipArchive from dying when
     * adding hundred of files.
     */

    protected function resetStack()
    {
        if (($this->stackSize++) === $this->descriptor)
        {
            $this->close();
            $this->open($this->filename, null);
            $this->stackSize = 0;
        }
    }
}