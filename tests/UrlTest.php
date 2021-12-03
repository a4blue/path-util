<?php

namespace A4blue\PathUtil\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use A4blue\PathUtil\Url;

class UrlTest extends TestCase
{
    /**
     * @dataProvider provideMakeRelativeTests
     * @covers A4blue\PathUtil\Url
     */
    public function testMakeRelative($absolutePath, $basePath, $relativePath)
    {
        $host = 'http://example.com';

        $relative = Url::makeRelative($host.$absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
        $relative = Url::makeRelative($absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    /**
     * @dataProvider provideMakeRelativeIsAlreadyRelativeTests
     * @covers A4blue\PathUtil\Url
     */
    public function testMakeRelativeIsAlreadyRelative($absolutePath, $basePath, $relativePath)
    {
        $host = 'http://example.com';

        $relative = Url::makeRelative($absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    /**
     * @dataProvider provideMakeRelativeTests
     * @covers A4blue\PathUtil\Url
     */
    public function testMakeRelativeWithFullUrl($absolutePath, $basePath, $relativePath)
    {
        $host = 'ftp://user:password@example.com:8080';

        $relative = Url::makeRelative($host.$absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    public function testMakeRelativeFailsIfInvalidUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The URL must be a string. Got: array');
        Url::makeRelative(array(), 'http://example.com/webmozart/puli');
    }

    public function testMakeRelativeFailsIfInvalidBaseUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The base URL must be a string. Got: array');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', array());
    }

    public function testMakeRelativeFailsIfBaseUrlNoUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"webmozart/puli" is not an absolute Url.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', 'webmozart/puli');
    }

    public function testMakeRelativeFailsIfBaseUrlEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"" is not an absolute Url.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', '');
    }

    public function testMakeRelativeFailsIfBaseUrlNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The base URL must be a string. Got: NULL');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', null);
    }


    public function testMakeRelativeFailsIfDifferentDomains()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The URL "http://example.com" cannot be made relative to "http://example2.com" since their host names are different.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', 'http://example2.com/webmozart/puli');
    }

    public function provideMakeRelativeTests()
    {
        return array(

            array('/webmozart/puli/css/style.css', '/webmozart/puli', 'css/style.css'),
            array('/webmozart/puli/css/style.css?key=value&key2=value', '/webmozart/puli', 'css/style.css?key=value&key2=value'),
            array('/webmozart/puli/css/style.css?key[]=value&key[]=value', '/webmozart/puli', 'css/style.css?key[]=value&key[]=value'),
            array('/webmozart/css/style.css', '/webmozart/puli', '../css/style.css'),
            array('/css/style.css', '/webmozart/puli', '../../css/style.css'),
            array('/', '/', ''),

            // relative to root
            array('/css/style.css', '/', 'css/style.css'),

            // same sub directories in different base directories
            array('/puli/css/style.css', '/webmozart/css', '../../puli/css/style.css'),

            array('/webmozart/puli/./css/style.css', '/webmozart/puli', 'css/style.css'),
            array('/webmozart/puli/../css/style.css', '/webmozart/puli', '../css/style.css'),
            array('/webmozart/puli/.././css/style.css', '/webmozart/puli', '../css/style.css'),
            array('/webmozart/puli/./../css/style.css', '/webmozart/puli', '../css/style.css'),
            array('/webmozart/puli/../../css/style.css', '/webmozart/puli', '../../css/style.css'),
            array('/webmozart/puli/css/style.css', '/webmozart/./puli', 'css/style.css'),
            array('/webmozart/puli/css/style.css', '/webmozart/../puli', '../webmozart/puli/css/style.css'),
            array('/webmozart/puli/css/style.css', '/webmozart/./../puli', '../webmozart/puli/css/style.css'),
            array('/webmozart/puli/css/style.css', '/webmozart/.././puli', '../webmozart/puli/css/style.css'),
            array('/webmozart/puli/css/style.css', '/webmozart/../../puli', '../webmozart/puli/css/style.css'),

            // first argument shorter than second
            array('/css', '/webmozart/puli', '../../css'),

            // second argument shorter than first
            array('/webmozart/puli', '/css', '../webmozart/puli'),

            array('', '', ''),
        );
    }

    public function provideMakeRelativeIsAlreadyRelativeTests()
    {
        return array(
            array('css/style.css', '/webmozart/puli', 'css/style.css'),
            array('css/style.css', '', 'css/style.css'),
            array('css/../style.css', '', 'style.css'),
            array('css/./style.css', '', 'css/style.css'),
            array('../style.css', '/', 'style.css'),
            array('./style.css', '/', 'style.css'),
            array('../../style.css', '/', 'style.css'),
            array('../../style.css', '', 'style.css'),
            array('./style.css', '', 'style.css'),
            array('../style.css', '', 'style.css'),
            array('./../style.css', '', 'style.css'),
            array('css/./../style.css', '', 'style.css'),
            array('css//style.css', '', 'css/style.css'),
        );
    }
}
