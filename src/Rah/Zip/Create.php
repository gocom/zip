<?php

/**
 * Creates an archive.
 */

class Rah_Zip_Create extends Rah_Zip_Base
{
    /**
     * Compresses a directory or a file.
     */

    protected function init()
    {
        $this->zip->open(ZIPARCHIVE::OVERWRITE);
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
                    $this->next();
                    continue;
                }

                if (($count++) === $this->config->descriptor_limit)
                {
                    $this->close();
                    $this->open();
                    $count = 0;
                }

                $name = $this->relativePath(realpath($source), $file->getPathname());

                if ($file->isDir())
                {
                    if ($zip->addEmptyDir($name) !== true)
                    {
                        throw new Exception('Unable add directory to the archive.');
                    }
                }
                else
                {
                    if ($zip->addFile($file->getPathname(), $name) !== true)
                    {
                        throw new Exception('Unable add file to the archive.');
                    }
                }

                $this->next();
            }
        }

        return $zip->close();
    }

    /**
     * Whether the file is ignored.
     *
     * @return string $file The filename
     */

    protected function isIgnored($file)
    {
        foreach ((array) $this->config->ignored as $f)
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