# PHP Коллекции

Библиотека для удобной работы с массивами данных различных структур с использованием функционального подхода. В основе лежат структуры данных, такие как: Список, Карта, Множество, Стек, Очередь. Структуры данных дополнены удобным функционалом обхода и преобразования коллекции.

Читать на других языках: [English](../README.md)

## Установка
```bash
composer require worksolutions/php-collections
``` 

## Пример использования
```php
<?php
use WS\Utils\Collections;
use WS\Utils\Collections\Functions;

// Отобрать элементы по фильтру
CollectionFactory::from([1, 2, 3])
    ->stream()
    ->filter(Predicates::moreThan(1))
    ->getCollection(); // Collection [2, 3]

// Распечатать все файлы в директории
CollectionFactory::fromIterable(new DirectoryIterator(__DIR__))
    ->stream()
    ->each(static function (SplFileInfo $fileInfo) {
        echo $fileInfo->getFilename() . "\n";
    });

```
