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

namespace Rah\Zip;

/**
 * Creates an archive from the given file or directory.
 *
 * If the specified file is a directory, all of its files
 * are added recursively to the archive.
 *
 * <code>
 * use Rah\Zip\Archive;
 * use Rah\Zip\Create;
 * $archive = new Archive();
 * $archive
 *    ->file('/path/to/archive.zip')
 *    ->source('/path/to/source/directory')
 *    ->tmp('/tmp');
 *
 * new Create($archive);
 * </code>
 */

class Create extends Base
{
    /**
     * Initializes.
     */

    protected function init()
    {
        $tmp = new \Rah_Eien_File();
        $tmp
            ->tmp($this->config->tmp)
            ->final($this->config->file);

        $file = new \Rah_Eien_Temporary_File($tmp);
        $this->zip->open($file->getFilename(), \ZIPARCHIVE::OVERWRITE);
        $this->pack();
        $this->zip->close();
        $file->move();
    }

    /**
     * Packages the given source files.
     */

    protected function pack()
    {
        $count = 0;

        foreach ((array) $this->config->source as $source) {
            if (($source = realpath($source)) === false || !file_exists($source) || !is_readable($source) || (!is_file($source) && !is_dir($source))) {
                throw new \Exception('Unable add source to the archive: ' . $source);
            }

            $this->zip->baseDirectory($source);

            if (is_dir($source)) {
                $files = new \RecursiveDirectoryIterator($source);
                $file = new \RecursiveIteratorIterator($files, \RecursiveIteratorIterator::SELF_FIRST);
            } else {
                $this->zip->addFile($source, basename($source));
                continue;
            }

            while ($file->valid()) {
                if ($file->isDot() || ($this->config->symlink === false && $file->isLink()) || $file->isReadable() === false) {
                    $file->next();
                    continue;
                }

                $name = $file->getPathname();

                if (in_array($name, (array) $this->config->ignore, true)) {
                    $file->next();
                    continue;
                }

                if ($file->isDir()) {
                    $this->zip->addEmptyDir($name);
                } else {
                    $this->zip->addFile($name);
                }

                $file->next();
            }
        }
    }
}
