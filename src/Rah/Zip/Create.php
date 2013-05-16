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
 * Creates an archive.
 */

class Rah_Zip_Create extends Rah_Zip_Base
{
    /**
     * Initializes.
     */

    protected function init()
    {
        $this->tmpFile();
        $this->open($this->temp, ZIPARCHIVE::OVERWRITE);
        $this->pack();
        $this->close();
        $this->move();
    }

    /**
     * Packages the given source files.
     */

    protected function pack()
    {
        $count = 0;

        foreach ((array) $this->config->source as $source)
        {
            if (($source = realpath($source)) === false || !file_exists($source) || !is_readable($source) || (!is_file($source) && !is_dir($source)))
            {
                throw new Exception('Unable add source to the archive: ' . $source);
            }

            if (is_dir($source))
            {
                $files = new RecursiveDirectoryIterator($source);
                $file = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::SELF_FIRST);
            }
            else
            {
                if ($this->zip->addFile($source, basename($source)) !== true)
                {
                    throw new Exception('Unable add file to the archive: ' . $source);
                }

                continue;
            }

            while ($file->valid())
            {
                if ($file->isDot() || ($this->config->symlink === false && $file->isLink()) || $file->isReadable() === false)
                {
                    $file->next();
                    continue;
                }

                if (($count++) === $this->config->descriptor)
                {
                    $this->close();
                    $this->open($this->temp, null);
                    $count = 0;
                }

                $name = $this->relativePath($source, $file->getPathname());

                if (in_array($name, (array) $this->config->ignore, true))
                {
                    $file->next();
                    continue;
                }

                if ($file->isDir())
                {
                    if ($this->zip->addEmptyDir($name) !== true)
                    {
                        throw new Exception('Unable add directory to the archive.');
                    }
                }
                else
                {
                    if ($this->zip->addFile($file->getPathname(), $name) !== true)
                    {
                        throw new Exception('Unable add file to the archive.');
                    }
                }

                $file->next();
            }
        }
    }

    /**
     * Gets a path relative to the given directory.
     *
     * @param string $directory
     * @param string $file
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
}