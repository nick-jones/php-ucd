# PHP UCD

[![Travis](https://img.shields.io/travis/nick-jones/php-ucd.svg?style=flat-square)](https://travis-ci.org/nick-jones/php-ucd)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/nick-jones/php-ucd.svg?style=flat-square)](https://scrutinizer-ci.com/g/nick-jones/php-ucd/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg?style=flat-square)](https://php.net/)

This project aims to provide a PHP interface into the [Unicode Character Database](http://unicode.org/ucd/) (UCD).
It provides a means to lookup, filter, and interrogate the metadata of characters that reside within the UCD.

This is still work-in-progress, and not yet intended for general purpose use.

## Installation

This will be added to Packagist once initial development is complete.

## Usage

Simply create an instance of `\UCD\UCD`, and utilise the methods on its interface. For example, if you wished to
dump all numeric characters, you could use something similar to:

```php
use UCD\Entity\Character;
use UCD\Entity\Character\Properties\Numericity\Numeric;
use UCD\View\CharacterView;
use UCD\UCD;

$filter = function (Character $character) {
    $properties = $character->getProperties();
    $numericity = $properties->getNumericity();

    return $numericity instanceof Numeric;
};

$ucd = new UCD();

foreach ($ucd->filterCharacters($filter) as $character) {
    $view = new CharacterView($character);
    printf("%s: %s\n", $character->getCodepoint(), $view->asUTF8());
}
```

If you wish to locate a character by it's decimal codepoint, call into the `::locateCharacter()` method. For example:

```php
$ucd = new \UCD\UCD();
$character = $ucd->locateCharacter(9731);
```

Note that the database also contains non-characters and surrogates. If you wish to interrogate these, you will need to
call into `::locate()` instead, which will return an object implementing `\UCD\Entity\CodepointAssigned`.

### Executable

The primary intention of this project is to act as a library, but a small utility command is available for testing and
database generation/manipulation purposes. `bin/ucd search <codepoint>` will dump character information, and
`bin/ucd repository-transfer <from> <to>` will transfer characters from one repository implementation to another.
Please run `bin/ucd` for more detailed help.

### Tests

PhpSpec and integration tests are provided. The easiest way to run them is via the Makefile; simply run `make test`.