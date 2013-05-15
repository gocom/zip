<?php

/**
 * Extracts an archive.
 */

class Rah_Zip_Extract extends Rah_Zip_Base
{
    /**
     * Initializes.
     */

    protected function init()
    {
        $this->open();

        if ($zip->extractTo($this->config->file) === false || $this->close())
        {
            throw new Exception('Unable to extract: ' . $this->config->file);
        }
    }
}