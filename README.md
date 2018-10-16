# DirectoryClassesIterator

This package provides two directory iterators to list PHP classes : 
* [`DirectoryClassesIterator`](src/DirectoryClassesIterator.php)
* [`RecursiveDirectoryClassesIterator`](src/RecursiveDirectoryClassesIterator.php)

The iterator loads the `.php` files using [`include_once()`](http://php.net/manual/function.include-once.php) and uses the [`ReflectionClass`](http://php.net/manual/class.reflectionclass.php) API to detect classes found within the loaded files.

## Usage
```php
<?php
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\DirectoryClassesIterator\DirectoryClassesIterator;

// recursive listing 
$iterator = new RecursiveDirectoryClassesIterator('/path/to/libriaries');

// recursive listing with specific extensions
$iterator = new RecursiveDirectoryClassesIterator('/path/to/libriaries', ['php', 'phtml', 'inc']);

// non recursive listing
$iterator = new DirectoryClassesIterator('/path/to/libriaries');
```  

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/directory-classes-iterator) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/directory-classes-iterator
```

## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).