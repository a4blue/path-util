File Path Utility
=================

PHP >= 7.3

This package provides robust, cross-platform utility functions for normalizing,
comparing and modifying file paths and URLs.

Usage
-----

Use the `Path` class to handle file paths:

```php
use A4blue\PathUtil\Path;

echo Path::canonicalize('/var/www/vhost/webmozart/../config.ini');
// => /var/www/vhost/config.ini

echo Path::canonicalize('C:\Programs\Webmozart\..\config.ini');
// => C:/Programs/config.ini

echo Path::canonicalize('~/config.ini');
// => /home/webmozart/config.ini

echo Path::makeAbsolute('config/config.yml', '/var/www/project');
// => /var/www/project/config/config.yml

echo Path::makeRelative('/var/www/project/config/config.yml', '/var/www/project/uploads');
// => ../config/config.yml

$paths = array(
    '/var/www/vhosts/project/httpdocs/config/config.yml',
    '/var/www/vhosts/project/httpdocs/images/banana.gif',
    '/var/www/vhosts/project/httpdocs/uploads/../images/nicer-banana.gif',
);

Path::getLongestCommonBasePath($paths);
// => /var/www/vhosts/project/httpdocs

Path::getFilename('/views/index.html.twig');
// => index.html.twig

Path::getFilenameWithoutExtension('/views/index.html.twig');
// => index.html

Path::getFilenameWithoutExtension('/views/index.html.twig', 'html.twig');
Path::getFilenameWithoutExtension('/views/index.html.twig', '.html.twig');
// => index

Path::getExtension('/views/index.html.twig');
// => twig

Path::hasExtension('/views/index.html.twig');
// => true

Path::hasExtension('/views/index.html.twig', 'twig');
// => true

Path::hasExtension('/images/profile.jpg', array('jpg', 'png', 'gif'));
// => true

Path::changeExtension('/images/profile.jpeg', 'jpg');
// => /images/profile.jpg

Path::join('phar://C:/Documents', 'projects/my-project.phar', 'composer.json');
// => phar://C:/Documents/projects/my-project.phar/composer.json

Path::getHomeDirectory();
// => /home/webmozart
```

Use the `Url` class to handle URLs:

```php
use A4blue\PathUtil\Url;

echo Url::makeRelative('http://example.com/css/style.css', 'http://example.com/puli');
// => ../css/style.css

echo Url::makeRelative('http://cdn.example.com/css/style.css', 'http://example.com/puli');
// => http://cdn.example.com/css/style.css
```

Learn more in the [Documentation] and the [API Docs].

Motivation
-------

There are some Libraries that still use the original Code, which is deprecated, but don't want to switch to symfony/filesystem .
In order to minimize Variations of the Code being copied to these Projects, i try to Hard-Fork the project and to maintain it,
so that these can potentially use this library

Authors
-------

* [Bernhard Schussek] a.k.a. [@webmozart] Original Author
* [Alexander Ratajczak] a.k.a. [@a4blue] 
* [The Community Contributors]

Documentation
-------------

Read the [Documentation] if you want to learn more about the contained functions.

Contribute
----------

Contributions are always welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at the [Git repository].

License
-------

All contents of this package are licensed under the [MIT license].