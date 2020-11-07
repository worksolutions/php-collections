# PHP Collections

![Tests](https://github.com/worksolutions/php-collections/workflows/Unit%20tests/badge.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/worksolutions/php-collections/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/worksolutions/php-collections/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/worksolutions/php-collections/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/worksolutions/php-collections/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/worksolutions/php-collections?style=flat-square)](https://packagist.org/packages/worksolutions/php-collections)

A collection library for PHP. It is used for convenient work with common data structures through functional approach. The main data structures are: List, Map, Stack, Queue, Set.
Part of the library is a Stream API. It provides a more functional programming approach to iterating and processing elements.

Read this in other languages: [Русский](doc/README.ru.md)

## Requirements 
[```PHP 7.1+```](https://www.php.net/downloads)

## Install 
```bash
composer require worksolutions/php-collections
``` 

## Examples
```php
<?php
use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

// Getting filtered elements
CollectionFactory::from([1, 2, 3])
    ->stream()
    ->filter(Predicates::greaterThan(1))
    ->getCollection(); // Collection [2, 3]

// Print directory files
CollectionFactory::fromIterable(new DirectoryIterator(__DIR__))
    ->stream()
    ->each(static function (SplFileInfo $fileInfo) {
        echo $fileInfo->getFilename() . "\n";
    });

```

## Basic concepts

The library is based on a consistent approach to data processing and transformation. Creation of a kind of transformation pipeline where you can perform certain steps sequentially. Each step is responsible only for its own little piece of work. In this case, the steps can be reused since they are atomic.

Fundamentally, the library consists of several parts, these are: 

- [Data structures.](#data-structures) Each structure has its own peculiarity, expressed through the interface and descriptions to it, and implementation. In this case, the implementation of the behavior of data structures can be different.
- [Collection factory.](#collection-factory) The collection factory has many static methods for conveniently creating collections.
- [Collection streams.](#collection-streams) Streams traverse and transform collections, with each transformation creating a new instance of the collection.
- [Traversal and transformation functions.](#traversal-and-transformation-functions) The function set consists of pre-prepared function constructors for easy use during the traversal. By their example, you can create and use your own functions that are more specific to your subject area.

## Data structures

The library is based on the most popular data structures that are self-sufficient for use without any extraneous libraries and classes. All other parts of the library rely on these structures, in particular the `Collection` interface. All collection data structures contain many elements with which you can perform basic procedures, such as: traversal, transformation, aggregation, etc.

- [Collection](#collection)
- [List (ListSequence)](#list-listsequence)
- [Set](#set)
- [Queue](#queue)
- [Stack](#stack)
- [Map](#map)

### Collection
[[↑ Data structures]](#data-structures)

Collection - the main base interface for collections. All data structures except for `maps (Map)` implement this interface, all additional functionality (Factories, Traversal threads) use this interface. To maintain universality in your applications, it is recommended to use the `Collection` interface, but only if it does not contradict the purpose of using the structure. 

Collections are traversable using a [```foreach```](#traversing-a-collection-with-a-foreach-loop) loop.

#### Interface methods

- [*add* – Adding an item to a collection](#add---adding-an-item-to-a-collection)
- [*addAll* – Adding multiple items to a collection](#addall---adding-multiple-items-to-a-collection)
- [*merge* – Merging collections](#merge--merging-collections)
- [*clear* – Removing all elements of the collection](#clear---removing-all-elements-of-the-collection)
- [*remove* – Removing a collection item](#remove---removing-a-collection-item)
- [*contains* – Checking for the existence of an element in a collection](#contains--checking-for-the-existence-of-an-element-in-a-collection)
- [*equals* – Comparing two collections for equivalence](#equals--comparing-two-collections-for-equivalence)
- [*size* – Getting the number of items in a collection](#size--getting-the-number-of-items-in-a-collection)
- [*isEmpty* – Checking a collection for emptiness](#isempty--checking-a-collection-for-emptiness)
- [*toArray* – Getting the elements of a collection as an array](#toarray---getting-the-elements-of-a-collection-as-an-array)
- [*copy* – Getting a copy of a collection](#copy---getting-a-copy-of-a-collection)
- [*stream* – Getting a collection traversal stream (Stream)](#stream--getting-a-collection-traversal-stream-stream)
- [Traversing a collection with a *foreach* loop](#traversing-a-collection-with-a-foreach-loop)

#### add - Adding an item to a collection
[[↑ Collection]](#collection)
```
add($element: mixed): bool;
```
Adds an item to the end of the collection. Returns `true` on success or` false` on failure.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2]); // [1, 2]
$collection->add(10); // [1, 2] -> [1, 2, 10];

```

#### addAll - Adding multiple items to a collection
[[↑ Collection]](#collection)
```
addAll($elements: iterable): bool;
```
Adds multiple items to the end of the collection. Returns `true` on success or` false` on failure.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2]); // [1, 2]
$collection->add([10, 11, 12]); // true
$collection->toArray(); // [1, 2] -> [1, 2, 10, 11, 12];

```

#### merge - Merging collections
[[↑ Collection]](#collection)
```
merge($collection: Collection): bool;
```
The method merges the current collection with the passed one. Returns `true` on success or` false` on failure.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2]); // [1, 2]
$mergingCollection = CollectionFactory::from([11, 12]); // [11, 12]
$collection->merge($mergingCollection); // true
$collection->toArray(); // [1, 2, 10, 11, 12];

```

#### clear - Removing all elements of the collection
[[↑ Collection]](#collection)
```
clear(): void;
```
The method removes all elements of the collection.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2]); // [1, 2]
$collection->clear(); // null
$collection->toArray(); // [];

```

#### remove - Removing a collection item
[[↑ Collection]](#collection)
```
remove($element: mixed): bool;
```

Removing a specific item in the collection. The method returns a sign of deleting an element. If the element did not exist, it will return `false`.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection->remove(2); // true
$collection->remove(4); // false
$collection->toArray(); // [1, 3];

```

#### contains - Checking for the existence of an element in a collection
[[↑ Collection]](#collection)
```
contains($element: mixed): bool;
```

Checking if a specific item exists in the collection. If the element does not exist, it will return `false`.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection->contains(2); // true
$collection->contains(4); // false

```

#### equals - Comparing two collections for equivalence
[[↑ Collection]](#collection)
```
equals($collection: Collection): bool;
```

The method checks that the passed collection is equal to the current one, which means that all the elements of one collection are contained in another collection and the number of elements is equal. In case of inequality of collections, it will return `false`.

```php

use WS\Utils\Collections\HashSet;

$set1 = new HashSet([1, 2, 3]);
$set2 = new HashSet([3, 2, 1]);
$set3 = new HashSet([3, 2]);

$set1->equals($set2); // true
$set2->equals($set1); // true
$set1->equals($set3); // false

```

#### size - Getting the number of items in a collection
[[↑ Collection]](#collection)
```
size(): int;
```

The method returns the number of elements in the collection. If the collection is empty - 0.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection->size(); // 3

$emptyCollection = CollectionFactory::from([]);
$emptyCollection->size(); // false

```

#### isEmpty - Checking a collection for emptiness
[[↑ Collection]](#collection)
```
isEmpty(): bool;
```
The method returns an attribute of an empty collection. If there are elements in the collection, it will return `false`.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection->isEmpty(); // false

$emptyCollection = CollectionFactory::from([]);
$emptyCollection->isEmpty(); // true

```

#### toArray - Getting the elements of a collection as an array
[[↑ Collection]](#collection)
```
toArray(): array;
```

An indexed array method consisting of collection elements, the order of the elements depends on the internal representation.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection->toArray(); // [1, 2, 3]

$emptyCollection = CollectionFactory::from([]);
$emptyCollection->toArray(); // []

```

#### copy - Getting a copy of a collection
[[↑ Collection]](#collection)
```
copy(): Collection;
```
The method returns an exact copy of the collection. Collections are mutable. This means that using modification methods modifies the collection; to ensure that the collection is immutable, it is recommended to use the copy method.
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$copyOfCollection = $collection->copy(); // Collection

$copyOfCollection === $collection; // false

```

#### stream - Getting a collection traversal stream (Stream)
[[↑ Collection]](#collection)
```
stream(): Stream;
```
Method an object that implements the collection traversal interface (Stream). The collection traversal thread is a very powerful tool, and in most cases you have to deal with it during development. [More ...](#Collection streams)
```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]
$collection
    ->stream()
    ->each(static function (int $el) {var_export($el);}); // 1 2 3

```

#### Traversing a collection with a foreach loop
[[↑ Collection]](#collection)

Collections are traversable in a * foreach * loop. The collection iteration order depends on the internal implementation of a particular class.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::from([1, 2, 3]); // [1, 2, 3]

foreach($collection as $item) {
    var_export($item);
}

```

### List (ListSequence)
[[↑ Data structures]](#data-structures)

A list is a data structure in which the order of elements is strictly defined. Lists with the same set of items and different ordering are not equal. The ListSequence interface extends the Collection interface.

ListSequence interface extends interfaces: [```Collection```](#collection)

Classes implement `ListSequence` interface: ```ArrayList, ImmutableList```

#### Interface methods

- [*get* – Getting an item by ordinal](#get---getting-an-item-by-ordinal)
- [*set* – Replacing a list item](#set---replacing-a-list-item)
- [*indexOf* – Getting the ordinal index of an element](#indexof---getting-the-ordinal-index-of-an-element)
- [*lastIndexOf* – Getting the ordinal index of the last matched item](#lastindexof---getting-the-ordinal-index-of-the-last-matched-item)
- [*removeAt* – Removing an element by index](#removeat---removing-an-element-by-index)

#### get - Getting an item by ordinal
[[↑ List (ListSequence)]](#list-listsequence)
```
get($index: int): mixed;
```
The method returns the item by index. If no element exists at the index, it will return null. The first element of the list has index 0.
```php

use WS\Utils\Collections\ArrayList;

$list = ArrayList::of(1, 2);

$list->get(0); // 1
$list->get(1); // 2
$list->get(2); // null

```

#### set - Replacing a list item
[[↑ List (ListSequence)]](#list-listsequence)
```
set($element: mixed, $index: int): mixed;
```
The method replaces the element by an index. Returns the value of the element that has been replaced by the current method. If an attempt is made to replace a non-existing element, an OutOfRangeException will be thrown.
```php

use WS\Utils\Collections\ArrayList;

$list = ArrayList::of(1, 2);

$list->set(3, 0); // 1
$list->set(4, 1); // 2
$list->set(4, 2); // OutOfRangeException

```

#### indexOf - Getting the ordinal index of an element
[[↑ List (ListSequence)]](#list-listsequence)
```
indexOf($element: mixed): ?int;
```
The method returns the first index of the found element. If there is no value in the list, it will return `null`.
```php

use WS\Utils\Collections\ArrayList;

$list = ArrayList::of(1, 2, 1, 3);

$list->indexOf(1); // 0
$list->indexOf(2); // 1
$list->indexOf(4); // null

```

#### lastIndexOf - Getting the ordinal index of the last matched item
[[↑ List (ListSequence)]](#list-listsequence)
```
lastIndexOf($element: mixed): ?int;
```
The method returns the last index of the found element. If there is no value in the list, it will return `null`.
```php

use WS\Utils\Collections\ArrayList;

$list = ArrayList::of(1, 2, 1, 3);

$list->indexOf(1); // 2
$list->indexOf(2); // 1
$list->indexOf(3); // null

```

#### removeAt - Removing an element by index
[[↑ List (ListSequence)]](#list-listsequence)
```
removeAt(int $index): mixed;
```
The method removes an element by an index, elements after the deleted index move closer to the beginning by one position. Returns the value of the removed item. If there is no element by the index, it will return `null`.
```php

use WS\Utils\Collections\ArrayList;

$list = ArrayList::of(1, 2, 1, 3);

$list->removeAt(1); // 2
$list->toArray(); // [1, 1, 3]

```

### Set
[[↑ Data structures]](#data-structures)

The set contains only unique elements, the order of the elements can be arbitrary. That is, adding an element using the `add` method does not guarantee its last place among other elements during iteration, and if there is an element with the same value, the latter will not be added to the set. 

Uniqueness is determined by the value, and for objects either by the uniqueness of a specific object, or, if the object implements the `HashCodeAware` interface, by the uniqueness of the result of calling the `getHashCode (): string;`.

`Set` interface extends base interfaces: [```Collection```](#collection)

Classes implement `Set` interface: ```HashSet```

### Queue
[[↑ Data structures]](#data-structures)

A data structure such as a queue is convenient for sequential processing of data in the order of arrival. It has convenient methods for adding and consuming items. The first element that got into the queue is the first and will leave it.

`Queue` interface extends base interface : [```Collection```](#collection)

Classes implements `Queue` interface: ```ArrayQueue```

#### Interface methods

- [*offer* – Inserting an item into the queue](#offer---inserting-an-item-into-the-queue)
- [*poll* – Getting an item and removing it from the queue](#poll---getting-an-item-and-removing-it-from-the-queue)
- [*peek* – Getting an item without removing it from the queue](#peek---getting-an-item-without-removing-it-from-the-queue)

#### offer - Inserting an item into the queue
[[↑ Queue]](#queue)
```
offer($element): bool;
```
The method adds an item to the end of the queue. Returns `false` if no item has been added, possibly in case of a limited queue.
```php

use WS\Utils\Collections\ArrayQueue;

$queue = ArrayQueue::of(1, 2);

$queue->offer(3); // [1, 2, 3]
$queue->peek(); // 3

```

#### poll - Getting an item and removing it from the queue
[[↑ Queue]](#queue)
```
poll(): mixed;
```
The method returns an element and removes it from the head of the queue. If there are no items in the queue, a `RuntimeException` will be thrown.
```php

use WS\Utils\Collections\ArrayQueue;

$queue = ArrayQueue::of(1, 2);

$queue->peek(); // 2
$queue->poll(); // [1]
$queue->peek(); // 1

```

#### peek - Getting an item without removing it from the queue
[[↑ Queue]](#queue)
```
poll(): mixed;
```
The method returns an element. In this case, the queue does not change, the element remains in its place. If there are no items in the queue, a `RuntimeException` will be thrown.
```php

use WS\Utils\Collections\ArrayQueue;

$queue = ArrayQueue::of(1, 2, 3);

$queue->peek(); // 2
$queue->size(); // 3

```

### Stack
[[↑ Data structures]](#data-structures)

The stack is a data structure, the logic of which is the opposite of the logic of the queue. The first item on the stack will be the first and popped from it.

`Stack` interface extends base interface : [```Collection```](#collection)

Classes implement `Stack` interface: ```ArrayStack```

#### Interface methods

- [*push* – Pushing an item](#push---pushing-an-item)
- [*pop* – Taking the last added item](#pop---taking-the-last-added-item)
- [*peek* – Getting the last added item without modifying the stack](#peek---getting-the-last-added-item-without-modifying-the-stack)

#### push - Pushing an item
[[↑ Stack]](#stack)
```
push($element: mixed): bool;
```
The method pushs an item to the top of the stack. Returns `false` if no element was added, possibly in case of restrictions.
```php

use WS\Utils\Collections\ArrayStack;

$queue = ArrayStack::of(1, 2);

$queue->push(3); // [1, 2, 3]
$queue->peek(); // 3

```

#### pop - Taking the last added item
[[↑ Stack]](#stack)
```
pop(): mixed;
```
The method returns the item from the top of the stack. At the top of the stack is the element added before the received one. If there are no elements on the stack, a `RuntimeException` will be thrown.
```php

use WS\Utils\Collections\ArrayStack;

$queue = ArrayStack::of(1, 2, 3); // [1, 2, 3]

$queue->pop(); // 3
$queue->pop(); // 2
$queue->push(4); // [1, 4]
$queue->pop(); // 4
$queue->peek(); // 1

```

#### peek - Getting the last added item without modifying the stack
[[↑ Stack]](#stack)
```
peek(): mixed;
```
The method returns the item at the top of the stack. If there are no elements on the stack, a `RuntimeException` will be thrown.
```php

use WS\Utils\Collections\ArrayStack;

$queue = ArrayStack::of(1, 2, 3); // [1, 2, 3]

$queue->pop(); // 3
$queue->peek(); // 3
$queue->peek(); // 3

$queue->pop(); // 2
$queue->pop(); // 1
$queue->peek(); // RuntimeException

```

### Map
[[↑ Data structures]](#data-structures)

Map interface represents a mapping, or in other words a dictionary, where each element represents a key-value pair. Map keys are unique in value, if they are objects, then uniqueness is achieved either by the uniqueness of the reference to the object, or if the object implements the `HashCodeAware` interface by the uniqueness of the result of calling the method` getHashCode (): string; `.

`Map` interface extends base interface: ```IteratorAggregate```

Classes implement `Stack` interface: ```HashMap```

#### Interface methods

- [*put* – Adding *key/value* pair](#put---adding-keyvalue-pair)
- [*get* – Getting the value of a pair by key](#get---getting-the-value-of-a-pair-by-key)
- [*keys* – Getting a collection of map keys](#keys---getting-a-collection-of-map-keys)
- [*values* – Getting a collection of map values](#values---getting-a-collection-of-map-values)
- [*remove* – Deleting a pair by key](#remove---deleting-a-pair-by-key)
- [*containsKey* – Sign of the presence of a key pair](#containskey---sign-of-the-presence-of-a-key-pair)
- [*containsValue* – Sign of the presence of a pair by value](#containsvalue---sign-of-the-presence-of-a-pair-by-value)
- [*size* – Number of pairs in the card](#size---number-of-pairs-in-the-card)
- [*stream* - Getting traverse stream with collection of key/value pair (Stream)](#stream---getting-traverse-streamw-with-collection-of-key-value-pair-stream)
- [Traverse map in _foreach_ loop](#Traverse-map-in-_foreach_-loop)

#### put - Adding key/value pair
[[↑ Map]](#map)
```
put($key: mixed, $value: mixed): bool;
```
The method adds a key / value pair to the structure object. Returns false if no item was added, possibly in case of restrictions. Both the key, and the value can be data of scalar types, arrays and objects.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

foreach ($map as $k => $v) {
    var_dump($k); // one | two
    var_dump($v); // 1   | 2
}

```

#### get - Getting the value of a pair by key
[[↑ Map]](#map)
```
get($key): mixed;
```
The method returns the value of the pair by the key `key`. If there is no value, it will return `null`.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

$map->get('one'); // 1
$map->get('three'); // null

```

#### keys - Getting a collection of map keys
[[↑ Map]](#map)
```
keys(): Collection<mixed>;
```
The method returns a collection consisting of all map keys.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

foreach ($map->keys() as $k) {
    var_dump($k); // one | two
}

```

#### values - Getting a collection of map values
[[↑ Map]](#map)
```
values(): Collection<mixed>;
```
The method returns a collection consisting of all the values of the map pairs.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

foreach ($map->keys() as $v) {
    var_dump($v); // 1 | 2
}

```

#### remove - Deleting a pair by key
[[↑ Map]](#map)
```
remove($key: mixed): bool;
```
The method removes a pair from the map by the key `key`.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

$map->remove('one');
foreach ($map->keys() as $v) {
    var_dump($v); // 2
}

```

#### containsKey - Sign of the presence of a key pair
[[↑ Map]](#map)
```
containsKey($key: mixed): bool;
```
The method returns an indication of the presence of a pair with the key `key`.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

$map->containsKey('one'); // true

```

#### containsValue - Sign of the presence of a pair by value
[[↑ Map]](#map)
```
containsValue($value: mixed): bool;
```
The method returns an indication of the presence of a pair with the value `value`.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

$map->containsValue(1); // true
$map->containsValue(3); // false

```

#### size - Number of pairs in the card
[[↑ Map]](#map)
```
size(): int;
```
The method returns the number of pairs.

```php

use WS\Utils\Collections\HashMap;

$map = new HashMap();
$map->put('one', 1);
$map->put('two', 2);

$map->size(); // 2

$emptyMap = new HashMap();
$map->size(); // 0

```

#### _stream_ - Getting traverse stream with collection of pair key/value (Stream)
[[↑ Map]](#map)
```
stream(): Stream;
```

Method returns object of Stream interface. Internal collection elements are pair of key/value. 

```php

use \WS\Utils\Collections\HashMap;
use \WS\Utils\Collections\MapEntry;

$map = new HashMap();

$map->put('one', 1);
$map->put('two', 2);
$map->put('tree', 3);

$map->stream()->each(static function (MapEntry $mapEntry) {
    var_export($mapEntry->getKey()); // 'one', 'two', 'three'
    var_export($mapEntry->getKey()); // 1    , 2    , 3
});

```

#### Traverse map in _foreach_ loop
[[↑ Map]](#map)

Object of Map interface can iterated in *foreach* loop. In this case keys and values will be passed before ones. Key can be of any type except an array.

```php

use \WS\Utils\Collections\HashMap;

$map = new HashMap();

$map->put(new SplObjectStorage(), 1);
$map->put(null, 2);
$map->put(false, 3);
$map->put(true, 4);
$map->put(0, 5);

foreach($map as $key => $value) {
    var_export($key);   // object of SplObjectStorage class| null| false| true| 0 
    var_export($value); // 1                               | 2   | 3    | 4   | 5
}

```

## Collection factory
[[↑ Up]](#php-collections)

The `CollectionFactory` factory allows you to create collection objects without using an implementation-specific constructor, or also has other convenient methods for creating collection objects. At the moment, the main structure of the library is `ArrayList`, which is generated by the factory in static methods.

### Example

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::numbers(10)
    ->stream()
    ->each(Consumers::dump()); // dumps int(0), int(1), ...

```

#### Collection creation factory methods
- [*from* – Generating a collection from an array of elements](#from---generating-a-collection-from-an-array-of-elements)
- [*fromIterable* – Generating a collection using any iterable](#fromiterable---generating-a-collection-using-any-iterable)
- [*numbers* – Generating a collection of a sequence of integers](#numbers---generating-a-collection-of-a-sequence-of-integers)
- [*generate* – Generating a collection using a generator](#generate---generating-a-collection-using-a-generator)

#### from - Generating a collection from an array of elements
[[↑ Collection factory]](#collection-factory)
```
from($values: array): Collection
```
The method creates a collection whose elements consist of the elements of the passed array.

At the moment, the implementation of the collection is `ArrayList`, in the future the specific implementation may change, when calling this method, you should rely only on the `Collection` interface.
```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::from([1 ,2, 3])
    ->stream()
    ->each(Consumers::dump()); // dumps int(1), int(2), int(3)

```

#### fromIterable - Generating a collection using any iterable
[[↑ Collection factory]](#collection-factory)
```
fromIterable($iterable: iterable): Collection
```
The method creates a collection whose elements consist of the elements of the passed iterator. 

At the moment, the implementation of the collection is `ArrayList`, in the future the specific implementation may change, when calling this method, you should rely only on the `Collection` interface.
```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;
use WS\Utils\Collections\Functions\Converters;

CollectionFactory::fromIterable(new DirectoryIterator(__DIR__))
    ->stream()
    ->map(Converters::toPropertyValue('filename'))
    ->each(Consumers::dump()); // Dumps strings with filenames
```

#### numbers - Generating a collection of a sequence of integers
[[↑ Collection factory]](#collection-factory)
```
numbers($from: int, $to: ?int): Collection
```
The method creates a collection whose elements consist of a sequence of integers `[$ from .. $ to]`. If the `$ to` parameter is not passed, the` [0 .. $ from] `collection will be returned.

At the moment, the implementation of the collection is `ArrayList`, in the future the specific implementation may change, when calling this method, you should rely only on the` Collection` interface.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::numbers(10, 15)
    ->stream()
    ->each(Consumers::dump()); // Dumps  [10, 11, 12, 13, 14, 15]
```

#### generate - Generating a collection using a generator
[[↑ Collection factory]](#collection-factory) 
```
generate($times: int, $generator: ?callable): Collection
```

The method creates a collection of size `$ times`, whose elements consist of the values of the results of calling the generator` $ generator`.

At the moment, the implementation of the collection is `ArrayList`, in the future the specific implementation may change, when calling this method, you should rely only on the` Collection` interface. If you need a specific type of a collection instance, you need to use implementation constructors or their static methods.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::generate(3, static function () {
        return random_int(0, 10);
    })
    ->stream()
    ->each(Consumers::dump()); // Dumps for example [9, 7, 2]
```

## Collection streams
[[↑ Up]](#php-collections)

For more convenient traversal and conversion of collections, you must use Streams. Basically all computation should be done via stream. To get a stream, you need to call the collection method `Collection::stream ()`. 

Processing a collection with stream occurs through the execution of functions, the interfaces of which must match the traversal / transformation purpose. There are six of them:
- *Predicate*. Used to filter elements `filter`, the element remains in the stream collection in case the predicate called for this element returns a boolean positive value. The library already contains some [prepared predicates](#predicates).
- *Converter*. Functions of this type are used to transform the `map` stream collection. The converter must return a value that, by some attribute, corresponds to the passed element of the collection. The library already contains some [prepared converters](#converters).
- *Consumer*. Consumer functions do not modify the stream collection, but are used to traverse the `each` collection. The passed function will be called for each element of the collection; the result of the function is not taken into account. The library already contains some [prepared consumers](#consumers).
- *Comparator*. Comparators participate in sorting items to determine the sort order of two values. Must return an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second. [php.net usort](https://www.php.net/manual/en/function.usort.php). The library already contains some [prepared comparison functions](#comparators).
- *Reorganizer*. Conversion functions convert one collection to another `reorganize` without changing the stream object. Transformations are necessary when you need to get the final collection not just by transforming one element to another, but to form a new collection based on all the information in the original collection. Examples are methods for shuffling shuffle elements or forming chunks. The library already contains some [prepared reorganizers](#reorganizers).
- *Collector*. Data collection functions are applied to a stream using the collect method, in fact, the result of executing the functions of this group will be the result of executing the stream. The collect method is terminal, that is, calling this method terminates the stream.

All streams belong to the `Stream` interface, which means that the description of the interface guarantees its correct execution regardless of the specific implementation, exceptions are only special cases of stream behavior (`when`).

Stream\`s guarantee that after each modification method the `getCollection` method will return different object instances, which is a safe way from a security point of view to maintain immutability during conversion.

#### Stream interface methods

- [*each* – Traversing collection items](#each---traversing-collection-items)
- [*walk* – Limited traversal of collection items](#walk---a-limited-traversal-of-collection-items)
- [*filter* – Filtering collection items](#filter---filtering-collection-items)
- [*map* – Converting stream collection items](#map---converting-stream-collection-items)
- [*reorganize* – Stream collection conversion](#reorganize---stream-collection-conversion)
- [*collect* – Collecting data](#collect---collecting-data)
- [*sort* – Sorting items in a collection](#sort---sorting-items-in-a-collection)
- [*sortBy* – Sorting collection items by value](#sortby-sorting-collection-items-by-value)
- [*sortDesc* – Sorting elements of a collection in reverse order](#sortdesc---sorting-elements-of-a-collection-in-reverse-order)
- [*sortByDesc* – Sorting elements of a collection by value in reverse order](#sortdesc---sorting-elements-of-a-collection-in-reverse-order)
- [*reduce* – Reducing a collection into a single value](#reduce---reducing-a-collection-into-a-single-value)
- [*when* – Constraining stream modification by condition](#when---constraining-stream-modification-by-condition)
- [*always* – Removing stream modification constrains](#always---removing-stream-modification-constrains)
- [*getCollection* – Getting a stream collection](#getcollection---getting-a-stream-collection)
- [*allMatch* – Full match of all elements by predicate](#allmatch---full-match-of-all-elements-by-predicate)
- [*anyMatch* – Partial match of all elements by predicate](#anymatch--partial-match-of-all-elements-by-predicate)
- [*findAny* – Getting an arbitrary collection item](#findany---getting-an-arbitrary-collection-item)
- [*findFirst* – Getting the first item in a collection](#findfirst--getting-the-first-item-in-a-collection)
- [*findLast* – Getting the last item in a collection](#findlast--getting-the-last-item-in-a-collection)
- [*min* – Getting the minimum element of a collection](#min---getting-the-minimum-element-of-a-collection)
- [*max* – Getting the maximum item in a collection](#max---getting-the-maximum-item-in-a-collection)
- [*reverse* – Reverse the elements of the collection](#reverse---reverse-the-elements-of-the-collection)
- [*limit* – Shrink the collection to the specified size](#limit---shrink-the-collection-to-the-specified-size)

#### each - Traversing collection items
[[↑ Streams]](#collection-streams)
```
each($consumer: <fn($element: mixed, $index: int): void>): Stream;
```
The method applies the $ consumer function to each element of the stream collection. The result of the function is not considered. Calling the `each` method does not change the collection of the stream, but at the same time accesses every item in the collection.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::numbers(10)
    ->stream()
    ->each(Consumers::dump()) // dumps each element
    ->each(static function ($el) { // prints strings 0, 1, 2, 3
        echo $el."\n"; 
    })
;

```

#### walk - A limited traversal of collection items
[[↑ Streams]](#collection-streams)
```
walk($consumer: <fn($element: mixed, $index: int): false|void>, $limit: ?int): Stream;
```
The method applies the `$consumer` function to each element of the stream collection, just as it does in the `each` method. The result of the function is not considered. Calling the `each` method does not change the collection of the stream, but at the same time accesses every item in the collection.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::numbers(10)
    ->stream()
    ->walk(Consumers::dump(), 5) // dumps only first 5 elements: 0, 1, 2, 3, 4
    ->walk(static function ($el) { // prints strings 0, 1, 2, 3. Method will be called only 5 times
        if ($el === 4) {
            return false;
        }
        echo $el."\n";
    })
;

```

#### filter - Filtering collection items
[[↑ Streams]](#collection-streams)
```
filter($predicate: <fn($element: mixed): bool>): Stream;
```
The method applies the $ predicate function to each element of the stream collection. If the call to the predicate from the elements returns a negative result - `false, 0, ', []`, the element is excluded from the collection.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    ->filter(static function (int $el): bool {
        return $el % 2 === 0;
    })
    ->getCollection() // returns only first 5 elements: 0, 2, 4, 6, 8
;

```

#### map - Converting stream collection items
[[↑ Streams]](#collection-streams)
```
map($converter: <fn($element: mixed): mixed>): Stream;
```
The method applies a stream to each element of the collection, replaces the passed elements of the collection with the results of the function execution.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    ->map(static function (int $el): int {
        return $el * 10;
    })
    ->getCollection() // returns 0, 10, 20, 30, 40, 50, 60, 70, 80, 90
;

```

#### reorganize - Stream collection conversion
[[↑ Streams]](#collection-streams)
```
reorganize($reorganizer: <fn($collection: Collection): Collection>): Stream;
```

The method applies `$reorganizer` to the inner collection, then replaces the inner collection with the result of the method call. Needed when conversions should be performed based on data from a complete collection.

```php

use WS\Utils\Collections\ArrayStack;
use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    // reverse collection 
    ->reorganize(static function (Collection $collection): Collection {
        $stack = new ArrayStack();
        foreach ($collection as $item) {
            $stack->push($item);
        }
        $reversed = CollectionFactory::empty();
        while (!$stack->isEmpty()) {
            $reversed->add($stack->pop());
        }
        return $reversed;
    })
    ->getCollection()
;

```

#### collect - Collecting data
[[↑ Streams]](#collection-streams)
```
collect($collector: <fn ($collection: Collection): mixed>): mixed;
```
The method applies `$collector` to the internal collection and returns the result. It is necessary when you need to perform the final action on the collection using a stream. Terminal method.

```php

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;

$sumOfElements = CollectionFactory::numbers(10)
    ->stream()
    // get sum of collection elements
    ->collect(static function (Collection $collection): int {
        $res = 0;
        foreach ($collection as $item) {
            $res += $item;
        }
        return $res;
    })
;

```

#### sort - Sorting items in a collection
[[↑ Streams]](#collection-streams)
```
sort($comparator: <fn($a: mixed, $b: mixed): int>): Stream;
```
The method sorts the items according to the work of the `$comparator`. The comparator determines the sort order of the two values. Must return an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second. [php.net usort](https://www.php.net/manual/en/function.usort.php)

```php

use WS\Utils\Collections\CollectionFactory;

$sortedCollection = CollectionFactory::generate(10, static function (): int {
        return random_int(0, 100);
    })
    ->stream()
    // get sorted collection
    ->sort(static function (int $a, int $b): int {
        return $a <=> $b;
    })
    ->getCollection()
;

```

#### sortDesc - Sorting elements of a collection in reverse order
[[↑ Streams]](#collection-streams)
```
sortDesc($comparator: <fn($a: mixed, $b: mixed): int>): Stream;
```
The method sorts the elements according to the work of the $ comparator, but unlike the usual sort function, the elements will be arranged in descending order. The compiler determines the sort order of the two values. Must return an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second.  [php.net usort](https://www.php.net/manual/en/function.usort.php)

```php

use WS\Utils\Collections\CollectionFactory;

$sortedDescendentCollection = CollectionFactory::generate(10, static function (): int {
        return random_int(0, 100);
    })
    ->stream()
    // get sorted collection in the reverse order
    ->sortDesc(static function (int $a, int $b): int {
        return $a <=> $b;
    })
    ->getCollection()
;

```

#### sortBy Sorting collection items by value
[[↑ Streams]](#collection-streams)
```
sortBy($extractor: <fn($el: mixed): scalar>): Stream;
```
The method sorts the elements according to the received value of the $ extractor function for each element. The function must return a scalar value to enable independent optimized sorting. The method of sorting by value in reverse order `sortByDesc` works similarly.

```php

use WS\Utils\Collections\CollectionFactory;

class Container {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$sortedCollection = CollectionFactory::generate(10, static function (): Container {
        return new Container(random_int(0, 100));
    })
    ->stream()
    // get sorted collection
    ->sortBy(static function (Container $container): int {
        return $container->getValue();
    })
    ->getCollection()
;

```

#### reduce - Reducing a collection into a single value
[[↑ Streams]](#collection-streams)
```
reduce($accumulator: <fn($el: mixed, $carry: mixed): mixed>): mixed;
```
The method converts the collection to a single value. The function is passed the values `$el` of the iterable element and the result of calling the same function on the previous element` $carry`. In the first iteration, `$carry === null`. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

$sumOfCollection = CollectionFactory::numbers(10)
    ->stream()
    // get sum of collection elements
    ->reduce(static function (int $el, ?int $carry = null): int {
        return $carry + $el;
    })
;

```

#### when - Constraining stream modification by condition
[[↑ Streams]](#collection-streams)
```
when($condition: bool): Stream;
```
The method restricts modification if `$condition` is not met and all modification and bypass methods will not be called. The opposite method is [`always`](#Removing stream modification constrains).

Locked methods: 
- [each](#each---traversing-collection-items)
- [walk](#walk---a-limited-traversal-of-collection-items)
- [filter](#filter---filtering-collection-items)
- [reorganize](#reorganize---stream-collection-conversion)
- [map](#map---converting-stream-collection-items)
- [sort](#sort---sorting-items-in-a-collection)
- [sortBy](#sortby-sorting-collection-items-by-value)
- [sortDesc](#sortdesc---sorting-elements-of-a-collection-in-reverse-order)
- [sortDescBy](#sortby-sorting-collection-items-by-value)
- [reverse](#reverse---reverse-the-elements-of-the-collection)
- [limit](#limit---shrink-the-collection-to-the-specified-size)

```php

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;

$randomElementSizeCollection = CollectionFactory::numbers(random_int(0, 20));

$onlyTenElements = $randomElementSizeCollection
    ->stream()
    // get collection elements only 10 items
    ->when($randomElementSizeCollection->size() > 10)
    ->limit(10)
    ->when($randomElementSizeCollection->size() < 10)
    ->reorganize(static function (Collection $collection) {
        for ($i = $collection->size(); $i < 10; $i++ ) {
            $collection->add($i);
        }
        return $collection;
    })
;

```

#### always - Removing stream modification constrains
[[↑ Streams]](#collection-streams)
```
always(): Stream;
```
If the stream was previously blocked for modifications via the [`when` condition](#Constraining stream modification by condition), the` always` method cancels the restrictions on further calls of modifying methods.

```php

use WS\Utils\Collections\CollectionFactory;

$collection = CollectionFactory::numbers(20);

$onlyTenElements = $collection
    ->stream()
    // get collection elements only 10 items
    ->when($collection->size() > 5)
    ->limit(5)
    ->always()
    ->map(static function (int $el): int {
        return $el * 10;
    })
    ->getCollection() // [0, 10, 20, 30, 40]
;

```

#### getCollection - Getting a stream collection
[[↑ Streams]](#collection-streams)
```
getCollection(): Collection;
```
The method returns the collection based on the previously performed conversions. Even if conversion methods are still called on the stream, the resulting collection will remain unchanged. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

$stream = CollectionFactory::numbers(10)
    ->stream();

$collection1 = $stream
    ->map(static function (int $el): int{
        return $el * 10;
    })
    ->getCollection()
;

$collection2 = $stream
    ->filter(static function (int $el): bool {
        return $el > 50;
    })
    ->getCollection()
;

$collection1->size() === $collection2->size(); // false

$collection2->toArray(); // [60, 70, 80, 90]

```

#### allMatch - Full match of all elements by predicate
[[↑ Streams]](#collection-streams)
```
allMatch($predicate: <fn($el: mixed): bool>): bool;
```
The method will return `true` if all calls to` $predicate` on the elements of the collection are true (`true`). Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    ->allMatch(static function (int $el): bool {
        return $el >= 1;
    }) // false, 0 is less than 1
;

```

#### anyMatch- Partial match of all elements by predicate
[[↑ Streams]](#collection-streams)
```
anyMatch(callable $predicate): bool;
```
The method will return `true` if at least one call to` $ predicate` over the elements of the collection is true (`true`). Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    ->allMatch(static function (int $el): bool {
        return $el > 0;
    }) // true, [1, 2, 3, 4, 5, 6, 7, 8, 9] are grate than 0
;

```

#### findAny - Getting an arbitrary collection item
[[↑ Streams]](#collection-streams)
```
findAny(): mixed;
```

The method returns an arbitrary element of the collection, or `null` if the collection is empty. Does not guarantee that the item is randomly selected. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10)
    ->stream()
    ->findAny() // for example - 5
;

```

#### findFirst- Getting the first item in a collection
[[↑ Streams]](#collection-streams)
```
findFirst(): mixed;
```

The method will return the first element of the collection, or `null` if the collection is empty. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->findFirst() // 0
;

```

#### findLast- Getting the last item in a collection
[[↑ Streams]](#collection-streams)
```
findLast(): mixed;
```

The method will return the last element of the collection, or `null` if the collection is empty. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->findLast() // 9
;

```

#### min - Getting the minimum element of a collection
[[↑ Streams]](#collection-streams)
```
min($comparator: <fn($a: mixed, $b: mixed): int>): mixed;
```

The method will return the smallest item in the collection as compared by the comparator function, or `null` if the collection is empty. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->reorganize(Reorganizers::shuffle())
    ->min(static function (int $a, int $b): int {
        return $a <=> $b;
    }) // 0
;

```

#### max - Getting the maximum item in a collection
[[↑ Streams]](#collection-streams)
```
max($comparator: <fn($a: mixed, $b: mixed): int>): mixed;
```

The method will return the largest item in the collection as compared by the comparator function, or `null` if the collection is empty. Terminal method.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->reorganize(Reorganizers::shuffle())
    ->max(static function (int $a, int $b): int {
        return $a <=> $b;
    }) // 9
;

```

#### reverse - Reverse the elements of the collection
[[↑ Streams]](#collection-streams)
```
reverse(): Stream;
```

Method converts the order of the elements to the reversed sequence.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->reverse()
    ->getCollection() // [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
;

```

#### limit - Shrink the collection to the specified size
[[↑ Streams]](#collection-streams)
```
limit(int $size): Stream;
```

The method reduces the number of elements to the specified size. If the number of elements is already less than specified in the `$size` limit, the number of elements will remain the same.

```php

use WS\Utils\Collections\CollectionFactory;

CollectionFactory::numbers(10) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] 
    ->stream()
    ->reverse()
    ->getCollection() // [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
;

```

## Traversal and transformation functions
[[↑ Up]](#php-collections)

The library contains constructors of the most popular functions that initiate functions to work through traversal and transformation threads with pre-prepared parameters.

For example:
```php

use \WS\Utils\Collections\Functions\Predicates;

$equal10Predicate = Predicates::equal(10);

$equal10Predicate(11); // false
$equal10Predicate(10); // true

```

Calling the constructor of the function `Predicates::equal(10)` will return a function for comparing the input parameter with the value 10.

The functions used in the library are divided into the following types:

- [*Predicates*](#predicates)
- [*Comparators*](#Comparators)
- [*Converters*](#Converters)
- [*Reorganizers*](#reorganizers)
- [*Consumers*](#Consumers)

Each function type must have a corresponding interface for use in specific stream methods. 

### Predicates
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)
A group of function constructors that are used to filter the stream collection. All methods return initialized functions with the interface: `<Fn($el: mixed): bool>`. Predicates also let you work with object properties. For example, a property of an object `myProperty` means the presence of a public property in the object called `myProperty` or the presence of a `getter` method -`getMyProperty`.
- [*lock* – Locking](#lock--locking)
- [*notResistance* – Passing all values](#notresistance--passing-all-values)
- [*notNull* – Checking values for emptiness](#notnull--checking-values-for-emptiness)
- [*eachEven* – Passing even call items](#eacheven---passing-even-call-items)
- [*nth* – Passing specified elements](#nth--passing-specified-elements)
- [*equal* – Equivalence check](#equal--equivalence-check)
- [*lockDuplicated* – Locking duplicate values](#lockduplicated--locking-duplicate-values)
- [*lessThan* – Checking a value for the "less" condition](#lessthan--checking-a-value-for-the-less-condition)
- [*lessOrEqual* – Checking a value for a "less than or equal" condition](#lessorequal--checking-a-value-for-a-less-than-or-equal-condition)
- [*greaterThan* – Checking a value for the "greater than" condition](#greaterthan--checking-a-value-for-the-greater-than-condition)
- [*greaterOrEqual* – Checking a value for a "greater than or equal" condition](#greaterorequal--checking-a-value-for-a-greater-than-or-equal-condition)
- [*not* – Checking a value for inequality](#not--checking-a-value-for-inequality)
- [*in* – Checking a value for being in group of values](#in--checking-a-value-for-being-in-group-of-values)
- [*notIn* – Checking a value for absence in group of values](#notin--checking-a-value-for-absence-in-group-of-values)
- [*where* – Checking object property for equivalence](#where--checking-object-property-for-equivalence)
- [*whereNot* – Checking object properties for inequality](#wherenot--checking-object-properties-for-inequality)
- [*whereIn* – Checking a property of an object for being in group of values](#wherein--checking-a-property-of-an-object-for-being-in-group-of-values)
- [*whereNotIn* – Checking an object property for absence in group of values](#wherenotin--checking-an-object-property-for-absence-in-group-of-values)
- [*whereGreaterThan* – Checking an object property for the "greater than" condition](#wheregrearerthan--checking-an-object-property-for-the-greater-than-condition)
- [*whereLessThan* – Checking an object property for the "less" condition](#wherelessthan--checking-an-object-property-for-the-less-condition)
- [*wheregGreaterOrEqual* – Checking an object property for a "greater than or equal" condition](#wheregreaterorequal--checking-an-object-property-for-a-greater-than-or-equal-condition)
- [*whereLessOrEqual* – Checking an object property for a "less than or equal" condition](#wherelessorequal--checking-an-object-property-for-a-less-than-or-equal-condition)

#### lock – Locking
[[↑ Predicates]](#predicates)
```
lock(): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that, for any set of input data, will return false. In essence, the method spawns a stream blocking function.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

$lockFunction = Predicates::lock();
CollectionFactory::numbers(1, 10)
    ->stream()
    ->filter($lockFunction)
    ->getCollection()
    ->isEmpty() // true
;
```

#### notResistance – Passing all values
[[↑ Predicates]](#predicates)
```
notResistance(): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that, for any set of input data, will return `true`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

$passFunction = Predicates::notResistance();
CollectionFactory::numbers(1, 10)
    ->stream()
    ->filter($passFunction)
    ->getCollection()
    ->size() // 10
;
```

#### notNull – Checking values for emptiness
[[↑ Predicates]](#predicates)
```
notNull(): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that, for any set of input data, will return `true`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

$notNullPassFunction = Predicates::notNull();
CollectionFactory::from([1, 10, null])
    ->stream()
    ->filter($notNullPassFunction)
    ->getCollection()
    ->size() // 2
;
```

#### eachEven - Passing even call items
[[↑ Predicates]](#predicates)
```
eachEven(): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that, every even call, will return `true` and, accordingly, in other cases -` false`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

$evenPassFunction = Predicates::eachEven();
CollectionFactory::from([1, 2, 3, 4, null, false])
    ->stream()
    ->filter($evenPassFunction)
    ->getCollection()
    ->toArray() // 2, 4, false
;
```

#### nth – Passing specified elements
[[↑ Predicates]](#predicates)
```
nth($number: int): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that every `$number` call - will return` true` and, accordingly, in other cases - `false`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

$thirdPassFunction = Predicates::nth(3);
CollectionFactory::from([1, 2, 3, 4, null, false])
    ->stream()
    ->filter($thirdPassFunction)
    ->getCollection()
    ->toArray() // 3, false
;
```

#### equal – Equivalence check
[[↑ Predicates]](#predicates)
```
equal($value: mixed): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that returns true when the collection item matches the value of `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, false])
    ->stream()
    ->filter(Predicates::equal(3))
    ->findFirst() // 3
;
```

#### lockDuplicated – Locking duplicate values
[[↑ Predicates]](#predicates)
```
lockDuplicated(): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function that returns true only for calls with unique members.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([3, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::lockDuplicated())
    ->getCollection()
    ->toArray() // [3, 2, 4, null]
;
```

#### lessThan – Checking a value for the "less" condition
[[↑ Predicates]](#predicates)
```
lessThan($value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing elements with the value `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::lessThan(4))
    ->getCollection()
    ->toArray() // [1, 2, 3, null, 3]
;
```

#### lessOrEqual – Checking a value for a "less than or equal" condition
[[↑ Predicates]](#predicates)
```
lessOrEqual($value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing elements with the value `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::lessOrEqual(2))
    ->getCollection()
    ->toArray() // [1, 2, null]
;
```

#### greaterThan – Checking a value for the "greater than" condition
[[↑ Predicates]](#predicates)
```
greaterThan($value: scalar): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for comparing elements with the value `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::greaterThan(2))
    ->getCollection()
    ->toArray() // [3, 4, 3]
;
```

#### greaterOrEqual – Checking a value for a "greater than or equal" condition
[[↑ Predicates]](#predicates)
```
greaterOrEqual($value: scalar): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for comparing elements with the value `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::greaterOrEqual(2))
    ->getCollection()
    ->toArray() // [2, 3, 4, 3]
;
```

#### not – Checking a value for inequality
[[↑ Predicates]](#predicates)
```
not($value: mixed): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for checking the inequality of collection elements with the value `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::not(3))
    ->getCollection()
    ->toArray() // [1, 2, 4, null]
;
```

#### in – Checking a value for being in group of values
[[↑ Predicates]](#predicates)
```
in($values: array): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function to check if elements are in an array `$values`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::in([null, 3]))
    ->getCollection()
    ->toArray() // [3, null, 3]
;
```

#### notIn – Checking a value for absence in group of values
[[↑ Predicates]](#predicates)
```
notIn($values: array): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for checking the absence of elements in the set `$values`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

CollectionFactory::from([1, 2, 3, 4, null, 3])
    ->stream()
    ->filter(Predicates::notIn([null, 3]))
    ->getCollection()
    ->toArray() // [1, 2, 4]
;
```

#### where – Checking object property for equivalence
[[↑ Predicates]](#predicates)
```
where($property: string, $value: mixed): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates the function of checking the property of the element object for equality to the value of `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::where('value', 0))
    ->getCollection()
    ->isEmpty() // false
;
```

#### whereNot – Checking object properties for inequality
[[↑ Predicates]](#predicates)
```
whereNot($property: string, $value: mixed): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates the function of checking the property of the element object for inequality with the value of `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereNot('value', 0))
    ->getCollection()
    ->toArray() // [#1, #2, #3, #4]
;
```

#### whereIn – Checking a property of an object for being in group of values
[[↑ Predicates]](#predicates)
```
whereIn($property: string, $values: array): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for checking whether the value of an object property is in the set `$values`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereIn('value', [0, 4, 9]))
    ->getCollection()
    ->toArray() // [#0, #4]
;
```

#### whereNotIn – Checking an object property for absence in group of values
[[↑ Predicates]](#predicates)
```
whereNotIn($property: string, $values: array): Closure; \\ <Fn($el: mixed): bool>
```

The method initiates a function for checking the absence of an object property value in the set `$values`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereIn('value', [0, 4, 9]))
    ->getCollection()
    ->toArray() // [#0, #4]
;
```

#### whereGreaterThan – Checking an object property for the "greater than" condition
[[↑ Predicates]](#predicates)
```
whereGreaterThan($property: string, $value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing the value of an object's property with `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereGreaterThan('value', 3))
    ->getCollection()
    ->toArray() // [#4]
;
```

#### whereLessThan – Checking an object property for the "less" condition
[[↑ Predicates]](#predicates)
```
whereLessThan($property: string, $value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing the value of an object's property with `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereLessThan('value', 3))
    ->getCollection()
    ->toArray() // [#0, #1, #2]
;
```

#### whereGreaterOrEqual – Checking an object property for a "greater than or equal" condition
[[↑ Predicates]](#predicates)
```
whereGreaterOrEqual($property: string, $value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing the value of an object's property with `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereGreaterOrEqual('value', 3))
    ->getCollection()
    ->toArray() // [#3, #4]
;
```

#### whereLessOrEqual – Checking an object property for a "less than or equal" condition
[[↑ Predicates]](#predicates)
```
whereLessOrEqual($property: string, $value: scalar): Closure; \\ <Fn($el: scalar): bool>
```

The method initiates a function for comparing the value of an object's property with `$value`.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Predicates;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

$c = 0;
CollectionFactory::generate(5, static function () use (& $c) {
        return new ValueObject($c++);
    })
    ->stream()
    ->filter(Predicates::whereLessOrEqual('value', 3))
    ->getCollection()
    ->toArray() // [#1, #2, #3]
;
```

### Comparators
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)

Comparison function constructors group. Comparison functions are needed when using sorting methods to arrange the elements in the correct order. The resulting sort functions have an interface `<Fn($a: mixed, $b: mixed): int>`, with the same logic as [https://www.php.net/manual/ru/function.usort]. 

- [*scalarComparator* - Comparison of scalar values](#scalarcomparator-comparison-of-scalar-values)
- [*objectPropertyComparator* – Comparing object properties](#objectpropertycomparator--comparing-object-properties)
- [*callbackComparator* – Defining a function to compare values](#callbackcomparator--defining-a-function-to-compare-values)

#### scalarComparator Comparison of scalar values
[[↑ Comparators]](#comparators)
```
scalarComparator(): Closure; \\ <Fn($a: scalar, $b: scalar): int>
```

The method initiates a function that compares two values. The comparison function returns an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Comparators;

CollectionFactory::generate(5, static function (): int {
        return random_int(0, 10);
    })
    ->stream()
    ->sort(Comparators::scalarComparator())
    ->getCollection()
    ->toArray() // sorted value, for example [2, 3, 6, 7, 8]
;
```

#### objectPropertyComparator – Comparing object properties
[[↑ Comparators]](#comparators)
```
objectPropertyComparator($property: string): Closure; \\ <Fn($a: object, $b: object): int>
```

The method initiates a function that compares two property values of objects. The comparison function returns an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Comparators;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

CollectionFactory::generate(5, static function () {
        return new ValueObject(random_int(0, 10));
    })    
    ->stream()
    ->sort(Comparators::objectPropertyComparator('value'))
    ->getCollection()
    ->toArray() // sorted ValueObject objects, for example [#2, #3, #6, #7, #8]
;
```

#### callbackComparator – Defining a function to compare values
[[↑ Comparators]](#comparators)
```
callbackComparator($fun: <Fn($value: mixed): scalar>): Closure; \\ <Fn($a: mixed, $b: mixed): int>
```

The method initiates a function that compares the two values based on their processing by the `$fun` function. The comparison function returns an integer less than, equal to, or greater than zero if the first argument is respectively less than, equal to, or greater than the second.

```php

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Comparators;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

CollectionFactory::generate(5, static function () {
        return new ValueObject(random_int(0, 10));
    })    
    ->stream()
    ->sort(Comparators::callbackComparator(static function (ValueObject $valueObject) {
        return $valueObject->getValue();
    }))
    ->getCollection()
    ->toArray() // sorted ValueObject objects, for example [#2, #3, #6, #7, #8]
;

```

### Converters
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)

A group of constructors of functions for converting elements. The result of the converter function is `<Fn($obj: mixed): mixed>`.

- [*toPropertyValue* – Convert each item in the collection to a property value](#topropertyvalue-convert-each-item-in-the-collection-to-a-property-value)
- [*toProperties* – Converting each item in a collection to an associative array](#toproperties-converting-each-item-in-a-collection-to-an-associative-array)

#### toPropertyValue Convert each item in the collection to a property value
[[↑ Converters]](#converters)
```
toPropertyValue($property: string): Closure; \\ <Fn($obj: object): mixed>
```

The method creates a function that returns the value of an object property.

```php

use WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Converters;

class ValueObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}

CollectionFactory::generate(5, static function (int $index): ValueObject {
        return new ValueObject($index);
    })
    ->stream()
    ->map(Converters::toPropertyValue('value'))
    ->getCollection()
    ->toArray() // [0, 1, 2, 3, 4 ]
;

```

#### toProperties Converting each item in a collection to an associative array
[[↑ Converters]](#converters)
```
toProperties($names: array<string>): Closure; \\ <Fn($obj: object): array>
```

The method initiates a function that returns an associative array of object properties, whose keys are property names.

```php

use WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Converters;

class Person {
    private $name;
    private $surname;
    
    public function __construct(string $name, string $surname) 
    {
        $this->name = $name;
        $this->surname = $surname;
    }
    
    public function getName(): string 
    {
        return $this->name;
    }
    
    public function getSurname(): string
    {
        return $this->surname;
    }
}

CollectionFactory::generate(1, static function (): Person {
        return new Person('Ivan', 'Ivanov');
    })
    ->stream()
    ->map(Converters::toProperties(['name', 'surname']))
    ->getCollection()
    ->toArray() // [['name' => 'Ivan', 'surname' => 'Ivanov']]
;

```

### Reorganizers
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)

Stream conversion method constructors. Unlike element transformation functions, where each element of the original collection corresponds to an element of the new collection given the position of the first, stream transformation methods create a derived new collection with an arbitrary number of elements.

- [*shuffle* – Changing the order of a collection item](#shuffle---changing-the-order-of-a-collection-item)
- [*random* – Getting random elements of a collection](#random---getting-random-elements-of-a-collection)
- [*chunk* – Splitting into multiple collections of a specified size](#chunk---splitting-into-multiple-collections-of-a-specified-size)
- [*collapse* – Getting a collection without additional nesting levels](#collapse---getting-a-collection-without-additional-nesting-levels)

#### shuffle - Changing the order of a collection item
[[↑ Reorganizers]](#reorganizers)
```
shuffle(): Closure; \\ <Fn(): Collection>
```

The method initiates a function that returns a new collection with reordered items. Items will follow in random order.

```php

use \WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::numbers(5)
    ->stream()
    ->reorganize(Reorganizers::shuffle())
    ->getCollection()
    ->toArray() // for example [0, 3, 1, 2, 4]
;

```

#### random - Getting random elements of a collection
[[↑ Reorganizers]](#reorganizers)
```
random($count = 1: int): Closure; \\ <Fn(): Collection>
```

The method initiates a function, the function returns a new collection that contains a random bounded set of elements in the original collection.

```php

use \WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::numbers(5)
    ->stream()
    ->reorganize(Reorganizers::random(2))
    ->getCollection()
    ->toArray() // for example [0, 3]
;

```

#### chunk - Splitting into multiple collections of a specified size
[[↑ Reorganizers]](#reorganizers)
```
chunk($size: int): Closure; \\ <Fn(): Collection>
```

The method initiates a function, the function returns a collection of collections whose number of elements is less than or equal to `$size`.

```php

use \WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::numbers(10)
    ->stream()
    ->reorganize(Reorganizers::chunk(2))
    ->getCollection()
    ->toArray() // for example [[0, 1], [2, 3], ...]
;

```

#### collapse - Getting a collection without additional nesting levels
[[↑ Reorganizers]](#reorganizers)
```
collapse(): Closure; \\ <Fn(): Collection>
```

The method initiates a function, the function returns a collection without nested containers. In this context, containers are iterable data structures. 

```php

use \WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Reorganizers;

CollectionFactory::generate(3, static function (int $i): array {
        return [$i*2, $i*2 + 1];
    }) // [[0, 1], [2, 3], [4, 5]]
    ->stream()
    ->reorganize(Reorganizers::collapse())
    ->getCollection()
    ->toArray() // for example [0, 1, 2, 3, 4, 5]
;

```

### Consumers
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)

Consumer function constructors. Contains one function for printing element values. Basically, each consumer function is developed individually in the project source code.

- [*dump* – Listing the values of collection items](#dump---listing-the-values-of-collection-items)

#### dump - Listing the values of collection items
[[↑ Consumers]](#Consumers)
```
dump(): Closure; \\ <Fn(): Collection>
```

The method initiates a function that prints the passed value to the output stream.

```php

use \WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Consumers;

CollectionFactory::numbers(5)
    ->stream()
    ->each(Consumers::dump()) // dumps each element of collection
;

```

### Aggregation
[[↑ Traversal and transformation functions]](#traversal-and-transformation-functions)

- [*by*](#by)
- [*addToSet*](#addToSet)
- [*agv*](#avg)
- [*count*](#count)
- [*first*](#first)
- [*last*](#last)
- [*max*](#max)
- [*min*](#min)
- [*sum*](#sum)
- [*addAggregator*](#addAggregator)

#### by
[[↑ Aggregation]](#Aggregation)
```
$group = Group::by($fieldName): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 25],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 70],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 20],
];

$result = CollectionFactory::from($items)
    ->stream()
    ->collect(Group::by('type'))
; // ->
[
    'groceries' => new \WS\Utils\Collections\ArrayList([
        ['name' => 'potato', 'type' => 'groceries', 'price' => 25],
        ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ]),
    'transport' => new \WS\Utils\Collections\ArrayList([
       ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
       ['name' => 'taxi', 'type' => 'transport', 'price' => 70],
   ]),
    'entertainment' => new \WS\Utils\Collections\ArrayList([
        ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
        ['name' => 'cinema', 'type' => 'entertainment', 'price' => 20],
    ]),
];

```

#### addToSet
[[↑ Aggregation]](#Aggregation)
```
Group::addToSet($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 25],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 70],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 20],
];

$aggregation = Group::by('type')->addToSet('name', 'list');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['list' => ['potato', 'milk']],
    'transport' => ['list' => ['taxi']],
    'entertainment' => ['list' => ['cinema']],
];

```

#### avg
[[↑ Aggregation]](#Aggregation)
```
Group::avg($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 50],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->avg('price', 'avg');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['avg' => 40],
    'transport' => ['avg' => 75],
    'entertainment' => ['avg' => 30],
];

```

#### count
[[↑ Aggregation]](#Aggregation)
```
Group::count($destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'tea', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->count('total');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['total' => 3],
    'transport' => ['total' => 1],
    'entertainment' => ['total' => 2],
];

```

#### first
[[↑ Aggregation]](#Aggregation)
```
Group::first($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->first('name', 'item');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['item' => 'potato'],
    'transport' => ['item' => 'taxi'],
    'entertainment' => ['item' => 'Knicks game'],
];

```

#### last
[[↑ Aggregation]](#Aggregation)
```
Group::last($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->last('name', 'item');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['item' => 'milk'],
    'transport' => ['item' => 'airplane'],
    'entertainment' => ['item' => 'cinema'],
];

```

#### max
[[↑ Aggregation]](#Aggregation)
```
Group::max($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->max('price', 'max');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['max' => 50],
    'transport' => ['max' => 100],
    'entertainment' => ['max' => 300],
];

```

#### min
[[↑ Aggregation]](#Aggregation)
```
Group::min($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->min('price', 'min');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['min' => 30],
    'transport' => ['min' => 50],
    'entertainment' => ['min' => 30],
];

```

#### sum
[[↑ Aggregation]](#Aggregation)
```
Group::sum($sourceKey, $destKey): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->sum('price', 'spent');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['spent' => 80],
    'transport' => ['spent' => 150],
    'entertainment' => ['spent' => 330],
];

```

#### addAggregator
[[↑ Aggregation]](#Aggregation)
```
Group::addAggregator($destKey, $callbackFn): Group;
```

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;
use \WS\Utils\Collections\Collection;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->addAggregator('names', function (Collection $collection) { 
    $result = [];
    foreach ($collection as $item) {
        $result[] = $item['name'];
    }
    return implode(', ', $result);
});
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['names' => 'potato, milk'],
    'transport' => ['names' => 'taxi, airplane'],
    'entertainment' => ['names' => 'Knicks game, cinema'],
];

```

All aggregators can be combined with each other to get the desired result

```php

use \WS\Utils\Collections\CollectionFactory;
use \WS\Utils\Collections\Functions\Group\Group;

$items = [
    ['name' => 'potato', 'type' => 'groceries', 'price' => 30],
    ['name' => 'milk', 'type' => 'groceries', 'price' => 50],
    ['name' => 'taxi', 'type' => 'transport', 'price' => 100],
    ['name' => 'airplane', 'type' => 'transport', 'price' => 50],
    ['name' => 'Knicks game', 'type' => 'entertainment', 'price' => 300],
    ['name' => 'cinema', 'type' => 'entertainment', 'price' => 30],
];

$aggregation = Group::by('type')->avg('price', 'avg')->min('price', 'min')->max('price', 'max');
$result = CollectionFactory::from($items)
    ->stream()
    ->collect($aggregation)
; // ->
[
    'groceries' => ['avg' => 40, 'min' => 30, 'max' => 50],
    'transport' => ['avg' => 75, 'min' => 50, 'max' => 100],
    'entertainment' => ['avg' => 165, 'min' => 30, 'max' => 300],
];

```