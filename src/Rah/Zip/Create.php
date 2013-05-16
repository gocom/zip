<?php

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
            if (is_dir($source))
            {
                $files = new RecursiveDirectoryIterator($source);
                $file = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::SELF_FIRST);
            }
            else
            {
                if ($this->addFile($source, basename($source)) !== true)
                {
                    throw new Exception('Unable add file to the archive.');
                }

                continue;
            }

            while ($file->valid())
            {
                if ($file->isDot() || $file->isLink() || $file->isReadable() === false || $this->isIgnored($file->getPathname()))
                {
                    $file->next();
                    continue;
                }

                if (($count++) === $this->config->descriptor)
                {
                    $this->close();
                    $this->open();
                    $count = 0;
                }

                $name = $this->relativePath(realpath($source), $file->getPathname());

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
     * Whether the file is ignored.
     *
     * @return string $file The filename
     */

    protected function isIgnored($file)
    {
        foreach ((array) $this->config->ignore as $f)
        {
            if (strpos($file, $f) !== false)
            {
                return true;
            }
        }

        return false;
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