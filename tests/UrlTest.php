<?php

declare(strict_types=1);

namespace A4blue\PathUtil\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use A4blue\PathUtil\Url;

class UrlTest extends TestCase
{
    /**
     * @dataProvider provideMakeRelativeTests
     */
    public function testMakeRelative(string $absolutePath, string $basePath, string $relativePath): void
    {
        $host = 'http://example.com';

        $relative = Url::makeRelative($host.$absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
        $relative = Url::makeRelative($absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    /**
     * @dataProvider provideMakeRelativeIsAlreadyRelativeTests
     */
    public function testMakeRelativeIsAlreadyRelative(string $absolutePath, string $basePath, string $relativePath): void
    {
        $host = 'http://example.com';

        $relative = Url::makeRelative($absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    /**
     * @dataProvider provideMakeRelativeTests
     */
    public function testMakeRelativeWithFullUrl(string $absolutePath, string $basePath, string $relativePath): void
    {
        $host = 'ftp://user:password@example.com:8080';

        $relative = Url::makeRelative($host.$absolutePath, $host.$basePath);
        $this->assertSame($relativePath, $relative);
    }

    public function testMakeRelativeFailsIfBaseUrlNoUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"webmozart/puli" is not an absolute Url.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', 'webmozart/puli');
    }

    public function testMakeRelativeFailsIfBaseUrlEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"" is not an absolute Url.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', '');
    }

    public function testMakeRelativeFailsIfDifferentDomains(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The URL "http://example.com" cannot be made relative to "http://example2.com" since their host names are different.');
        Url::makeRelative('http://example.com/webmozart/puli/css/style.css', 'http://example2.com/webmozart/puli');
    }

    /**
     * @return array<string[]>
     */
    public function provideMakeRelativeTests(): array
    {
        return [

            ['/webmozart/puli/css/style.css', '/webmozart/puli', 'css/style.css'],
            ['/webmozart/puli/css/style.css?key=value&key2=value', '/webmozart/puli', 'css/style.css?key=value&key2=value'],
            ['/webmozart/puli/css/style.css?key[]=value&key[]=value', '/webmozart/puli', 'css/style.css?key[]=value&key[]=value'],
            ['/webmozart/css/style.css', '/webmozart/puli', '../css/style.css'],
            ['/css/style.css', '/webmozart/puli', '../../css/style.css'],
            ['/', '/', ''],

            // relative to root
            ['/css/style.css', '/', 'css/style.css'],

            // same sub directories in different base directories
            ['/puli/css/style.css', '/webmozart/css', '../../puli/css/style.css'],

            ['/webmozart/puli/./css/style.css', '/webmozart/puli', 'css/style.css'],
            ['/webmozart/puli/../css/style.css', '/webmozart/puli', '../css/style.css'],
            ['/webmozart/puli/.././css/style.css', '/webmozart/puli', '../css/style.css'],
            ['/webmozart/puli/./../css/style.css', '/webmozart/puli', '../css/style.css'],
            ['/webmozart/puli/../../css/style.css', '/webmozart/puli', '../../css/style.css'],
            ['/webmozart/puli/css/style.css', '/webmozart/./puli', 'css/style.css'],
            ['/webmozart/puli/css/style.css', '/webmozart/../puli', '../webmozart/puli/css/style.css'],
            ['/webmozart/puli/css/style.css', '/webmozart/./../puli', '../webmozart/puli/css/style.css'],
            ['/webmozart/puli/css/style.css', '/webmozart/.././puli', '../webmozart/puli/css/style.css'],
            ['/webmozart/puli/css/style.css', '/webmozart/../../puli', '../webmozart/puli/css/style.css'],

            // first argument shorter than second
            ['/css', '/webmozart/puli', '../../css'],

            // second argument shorter than first
            ['/webmozart/puli', '/css', '../webmozart/puli'],

            ['', '', ''],
        ];
    }

    /**
     * @return array<string[]>
     */
    public function provideMakeRelativeIsAlreadyRelativeTests(): array
    {
        return [
            ['css/style.css', '/webmozart/puli', 'css/style.css'],
            ['css/style.css', '', 'css/style.css'],
            ['css/../style.css', '', 'style.css'],
            ['css/./style.css', '', 'css/style.css'],
            ['../style.css', '/', 'style.css'],
            ['./style.css', '/', 'style.css'],
            ['../../style.css', '/', 'style.css'],
            ['../../style.css', '', 'style.css'],
            ['./style.css', '', 'style.css'],
            ['../style.css', '', 'style.css'],
            ['./../style.css', '', 'style.css'],
            ['css/./../style.css', '', 'style.css'],
            ['css//style.css', '', 'css/style.css'],
        ];
    }
}
