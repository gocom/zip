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
 * Extracts an archive.
 *
 * <code>
 * use Rah\Zip\Archive;
 * use Rah\Zip\Extract;
 * $archive = new Archive();
 * $archive
 *    ->file('/path/to/archive.zip')
 *    ->target('/path/to/target/directory')
 *    ->tmp('/tmp');
 *
 * new Extract($archive);
 * </code>
 */

class Extract extends Base
{
    /**
     * Initializes.
     */

    protected function init()
    {
        $tmp = new \Rah_Eien_File();
        $tmp
            ->tmp($this->config->tmp)
            ->file($this->config->file);

        $file = new \Rah_Eien_Temporary_Make($tmp);
        $this->zip->open($file->getFilename(), null);
        $this->zip->extractTo($this->config->target);
        $this->zip->close();
        $file->trash();
    }
}
