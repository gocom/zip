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

class Rah_Zip_Archive_ZipArchive implements Rah_Zip_Archive_Template
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
     * {@inheritdoc}
     */

    public function __construct()
    {
        if (class_exists('ZipArchive'))
        {
            $this->zip = new ZipArchive();
        }
        else
        {
            throw new Rah_Zip_Archive_Exception('ZipArchive is not installed.');
        }
    }

    /**
     * {@inheritdoc}
     */

    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */

    public function open($filename, $flags = ZIPARCHIVE::OVERWRITE)
    {
        if ($this->zip->open($filename, $flags) !== true)
        {
            throw new Rah_Zip_Archive_Exception('Unable to open: '.$filename);
        }
        else
        {
            $this->filename = $filename;
            $this->isOpen = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function close()
    {
        if ($this->isOpen === true)
        {
            if ($this->zip->close() !== true)
            {
                throw new Rah_Zip_Archive_Exception('Unable to close: '.$this->filename);
            }

            $this->isOpen = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function addFile($filename)
    {
        $this->resetStack();
        $localname = $this->relativePath($filename);

        if ($this->zip->addFile($filename, $localname) !== true)
        {
            throw new Rah_Zip_Archive_Exception('Unable to add a file to the archive: '.$localname);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function addEmptyDir($localname)
    {
        $this->resetStack();
        $localname = $this->relativePath($localname);

        if ($this->zip->addEmptyDir($localname) !== true)
        {
            throw new Rah_Zip_Archive_Exception('Unable to add a directory to the archive: '.$localname);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function addFromString($localname, $contents)
    {
        $this->resetStack();
        $localname = $this->normalizePath($localname);

        if ($this->zip->addFromString($localname, $contents) !== true)
        {
            throw new Rah_Zip_Archive_Exception('Unable to add a file from a string to the archive: '.$localname);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function baseDirectory($directory)
    {
        if (is_file($directory))
        {
            $directory = dirname($directory);
        }

        $this->basepath = $this->normalizePath($directory);
        return $this;
    }

    /**
     * {@inheritdoc}
     */

    public function extractTo($destination, $entries = null)
    {
        if ($this->zip->extractTo($filename) !== true)
        {
            throw new Rah_Zip_Archive_Exception('Unable to extract to: '.$filename);
        }

        return $this;
    }

    /**
     * Reset the current stack.
     *
     * This prevents ZipArchive from dying when
     * adding hundreds of files.
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
        $file = $this->normalizePath($file);

        if (strpos($file.'/', $this->basepath.'/') === 0)
        {
            return substr($file, strlen($this->basepath) + 1);
        }

        return $file;
    }
}