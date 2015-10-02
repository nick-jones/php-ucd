# PHP UCD

[![Build Status](https://travis-ci.org/nick-jones/php-ucd.svg?branch=master)](https://travis-ci.org/nick-jones/php-ucd)

This project aims to provide a PHP interface into the [Unicode Character Database](http://unicode.org/ucd/) (UCD).
It provides a means to lookup, filter, and interrogate the metadata of characters that reside within the UCD.

This is still work-in-progress, and not yet intended for general purpose use.

## Installation

This will be added to Packagist once initial development is complete.

## Usage

Simply create an instance of `\UCD\UCD`, and utilise the methods on its interface. For example, if you wished to
dump all numeric characters, you could use something similar to:

```php
$filter = function (\UCD\Entity\Character $character) {
    $numericity = $character->getProperties()
        ->getNumericity();

    return $numericity instanceof \UCD\Entity\Character\Properties\Numericity\Numeric;
};

$ucd = new \UCD\UCD();

foreach ($ucd->filterCharacters($filter) as $character) {
    $view = new \UCD\View\CharacterView($character);
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