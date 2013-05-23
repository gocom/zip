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
 * Template for ZipArchive implementation.
 */

interface Rah_Zip_Archive_Template
{
    /**
     * Constructor.
     *
     * @throws Rah_Zip_Archive_Exception
     */

    public function __construct();

    /**
     * Destructor.
     *
     * Cleans up any trash and closes the file.
     */

    public function __destruct();

    /**
     * Opens a archive.
     *
     * @param  string $filename The filename
     * @param  int    $flags    The flags
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function open($filename, $flags = ZIPARCHIVE::OVERWRITE);

    /**
     * Closes the file.
     *
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function close();

    /**
     * Adds a file to the archive.
     *
     * @param  string $file The filename
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function addFile($filename);

    /**
     * Adds an empty directory to the archive.
     *
     * @param  string $localname The filename inside the archive
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function addEmptyDir($localname);

    /**
     * Adds a file to the archive from a string presenting the contents.
     *
     * @param  string $localname  The filename
     * @param  string $contents   The file contents
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function addFromString($localname, $contents);

    /**
     * Sets the base directory.
     *
     * Any children files added to the archive will be
     * relative to the given directory, snipping off
     * the beginning of the path.
     *
     * If $directory points to a file, its current
     * directory is used.
     *
     * This setting doesn't resolve links, or really even
     * query the filesystem. The path is treated as a string,
     * and merely compared to the given file paths.
     *
     * @param  string $directory Path to the directory
     * @return Rah_Zip_Archive_Template
     */

    public function baseDirectory($directory);

    /**
     * Extracts an archive to the given location.
     *
     * @param  string       $destination   The location the archive is extracted to
     * @param  string|array $entries       The entries to extract by name
     * @return Rah_Zip_Archive_Template
     * @throws Rah_Zip_Archive_Exception
     */

    public function extractTo($destination, $entries = null);
}