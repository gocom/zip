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
        $this->tmpFile();

        if (copy((string) $this->config->source, $this->temp) === false)
        {
            throw new Exception('Unable to create a temporary file.');
        }

        $this->open($this->temp, null);

        if ($zip->extractTo($this->config->file) === false || $this->close())
        {
            throw new Exception('Unable to extract to: ' . $this->config->file);
        }

        $this->clean();
    }
}