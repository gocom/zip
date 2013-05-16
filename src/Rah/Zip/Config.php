<?php

/**
 * The configuration options.
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
     * The file descriptor limit and a reset point.
     *
     * @var int 
     */

    public $descriptor = 200;

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
}