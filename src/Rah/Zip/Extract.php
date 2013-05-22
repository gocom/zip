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
 * Extracts an archive.
 *
 * @example
 * $zip = new Rah_Zip_Archive();
 * $zip
 *    ->file('/path/to/archive.zip')
 *    ->target('/path/to/target/directory')
 *    ->tmp('/tmp');
 *
 * new Rah_Zip_Extract($zip);
 */

class Rah_Zip_Extract extends Rah_Zip_Base
{
    /**
     * Initializes.
     */

    protected function init()
    {
        $this->tmpFile();

        if (copy((string) $this->config->file, $this->temp) === false)
        {
            throw new Exception('Unable to create a temporary file.');
        }

        $this->zip->open($this->temp, null);
        $this->zip->extractTo($this->config->target);
        $this->zip->close();
        $this->clean();
    }
}