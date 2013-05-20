<?php

class Rah_Zip_Test_BasicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testExtract()
    {
        return true;
    }

    public function tearDown()
    {
        unlink($this->temp);
    }
}