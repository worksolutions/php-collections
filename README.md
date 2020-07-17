# PHP Collections

![Tests](https://github.com/worksolutions/php-collections/workflows/Unit%20tests/badge.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/worksolutions/php-collections/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/worksolutions/php-collections/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/worksolutions/php-collections/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/worksolutions/php-collections/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/worksolutions/php-collections?style=flat-square)](https://packagist.org/packages/worksolutions/php-collections)

A collection library for PHP. It is used for convenient work with common data structures through functional approach. The main data structures are: List, Map, Stack, Queue, Set.
Part of the library is a Stream API. It provides a more functional programming approach to iterating and processing elements.

Read this in other languages: [Русский](doc/README.ru.md)

## Requirements 
[```PHP 7.2+```](https://www.php.net/downloads)

## Install 
```bash
composer require worksolutions/php-collections
``` 

## Examples
```php
<?php
use WS\Utils\Collections;
use WS\Utils\Collections\Functions;

// Getting filtered elements
CollectionFactory::from([1, 2, 3])
    ->stream()
    ->filter(Predicates::moreThan(1))
    ->getCollection(); // Collection [2, 3]

// Print directory files
CollectionFactory::fromIterable(new DirectoryIterator(__DIR__))
    ->stream()
    ->each(static function (SplFileInfo $fileInfo) {
        echo $fileInfo->getFilename() . "\n";
    });

```
