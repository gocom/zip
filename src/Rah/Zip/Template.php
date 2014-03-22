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
 * The base interface.
 */

interface Rah_Zip_Template
{
    /**
     * Constructor.
     *
     * @param Rah_Zip_Config           $config
     * @param Rah_Zip_Archive_Template $zip
     */

    public function __construct(Rah_Zip_Config $config, Rah_Zip_Archive_Template $zip = null);

    /**
     * Destructor.
     *
     * Closes open files and cleans trash.
     */

    public function __destruct();

    /**
     * Returns a path to the archive.
     *
     * @return string
     */

    public function __toString();
}
