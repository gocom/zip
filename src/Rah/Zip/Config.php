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
 * The configuration options.
 *
 * @example
 * class MyAppConfig extends Rah_Zip_Config
 * {
 *     public $file = '/path/to/archive.zip';
 *     public $source = '/path/to/source';
 *     public $tmp = '/tmp';
 * }
 */

abstract class Rah_Zip_Config
{
    /**
     * The ZIP archive filename.
     *
     * @var string
     */

    public $file;

    /**
     * Source files or directories.
     *
     * @var string|array
     */

    public $source = array();

    /**
     * Path to extract to.
     *
     * @var string
     */

    public $target;

    /**
     * An array of ignored files.
     *
     * @var array
     */

    public $ignore = array();

    /**
     * Path to the temporary directory.
     *
     * @var string
     */

    public $tmp = '/tmp';

    /**
     * Include symbolics links.
     *
     * If TRUE, symlinks are resolved and
     * target files stored in the archive.
     * Otherwise ignored.
     *
     * @var bool
     */

    public $symlink = false;
}