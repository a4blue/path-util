<?php

declare(strict_types=1);

namespace A4blue\PathUtil;

use InvalidArgumentException;

final class Url
{
    /**
     * Turns a URL into a relative path.
     *
     * The result is a canonical path. This class is using functionality of Path class.
     *
     * @see Path
     *
     * @param string $url     A URL to make relative.
     * @param string $baseUrl A base URL.
     *
     * @return string
     *
     * @throws InvalidArgumentException If the URL and base URL does
     *                                  not match.
     */
    public static function makeRelative(string $url, string $baseUrl): string
    {
        if (false === \strpos($baseUrl, '://')) {
            throw new InvalidArgumentException(\sprintf('"%s" is not an absolute Url.', $baseUrl));
        }

        list($baseHost, $basePath) = self::split($baseUrl);

        if (false === strpos($url, '://')) {
            if (0 === strpos($url, '/')) {
                $host = $baseHost;
            } else {
                $host = '';
            }
            $path = $url;
        } else {
            list($host, $path) = self::split($url);
        }

        if ('' !== $host && $host !== $baseHost) {
            throw new InvalidArgumentException(sprintf(
                'The URL "%s" cannot be made relative to "%s" since their host names are different.',
                $host,
                $baseHost
            ));
        }

        return Path::makeRelative($path, $basePath);
    }

    /**
     * Splits a URL into its host and the path.
     *
     * ```php
     * list ($root, $path) = Path::split("http://example.com/webmozart")
     * // => array("http://example.com", "/webmozart")
     *
     * list ($root, $path) = Path::split("http://example.com")
     * // => array("http://example.com", "")
     * ```
     *
     * @param string $url The URL to split.
     *
     * @return string[] An array with the host and the path of the URL.
     *
     * @throws InvalidArgumentException If $url is not a URL.
     */
    private static function split(string $url): array
    {
        $pos = strpos($url, '://');
        $scheme = substr($url, 0, $pos + 3);
        $url = substr($url, $pos + 3);

        if (false !== ($pos = strpos($url, '/'))) {
            $host = substr($url, 0, $pos);
            $url = substr($url, $pos);
        } else {
            // No path, only host
            $host = $url;
            $url = '/';
        }

        // At this point, we have $scheme, $host and $path
        $root = $scheme.$host;

        return [$root, $url];
    }
}
