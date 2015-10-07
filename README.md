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

The primary interface to utilise is `UCD\Database`. This provides a number of methods to interrogate "codepoint assigned"
entities (i.e. `Character`, `NonCharacter`, and `Surrogate` instances) that reside within the UCD:

- `::locate(int $codepoint)` - resolves a codepoint assigned entity
- `::locateCharacter(int $codepoint)` - as above, but will only return `Character` instances
- `::all()` - returns an iterator for the entire database
- `::allCharacters()` - as above, but only iterating over `Character` instances
- `::filter(callable $filter)` - filters the database by using the return value of the supplied callable
- `::filterCharacters(callable $filter)` - as above, but only filtering over `Character` instances
- `::walk(callable $callback)` - walks the entire database, calling into the supplied callback with each entity
- `::walkCharacters(callable $callback)` - as above, but only walking over `Character` instances

The `UCD\Database` class defaults to using a generated dump of the UCD located within
[`resources/generated/ucd`](resources/generated/ucd). This, whilst not the most efficient access the dataset, does
provide the most reasonable means to provide out of the box functionality. It is possible to leverage different
repository implementations by providing an instance of `UCD\Entity\Character\Repository` to the constructor of
`UCD\Database`.

Because this project makes good use of [generators](https://php.net/generators), the memory footprint of
interrogating the database is nominal.

### Examples

Say you wished to dump all characters that are numeric and reside outside of the Basic Latin (ASCII) block. You could
simply leverage the `::filter(callable $filter)` method, as described above, to interrogate the properties of each
`Character` instance. You could then perhaps dump their latin equivalent representation by calling `::getNumber()` on
the `Numericity` property. For example:

```php
use UCD\Entity\Character;
use UCD\Entity\Character\Properties\General\Block;
use UCD\View\CharacterView;
use UCD\Database;

$filter = function (Character $character) {
    $properties = $character->getProperties();
    $general = $properties->getGeneral();
    $block = $general->getBlock();

    return $properties->isNumeric()
        && !$block->equals(Block::fromValue(Block::BASIC_LATIN));
};

$ucd = new Database();

foreach ($ucd->filterCharacters($filter) as $character) {
    $codepoint = $character->getCodepoint();
    $properties = $character->getProperties();
    $numerity = $properties->getNumericity();
    $number = $numerity->getNumber();
    $view = new CharacterView($character);
    $utf8 = $view->asUTF8();

    printf("%s: %s (equivalent to %s)\n", $codepoint, $utf8, $number);
}

// outputting:
//  U+B2: ² (equivalent to 2)
//  U+B3: ³ (equivalent to 3)
//  U+B9: ¹ (equivalent to 1)
//  U+BC: ¼ (equivalent to 1/4)
//  U+BD: ½ (equivalent to 1/2)
//  U+BE: ¾ (equivalent to 3/4)
//  U+660: ٠ (equivalent to 0)
//  U+661: ١ (equivalent to 1)
//  U+662: ٢ (equivalent to 2)
//  U+663: ٣ (equivalent to 3)
//  <snip>
```

Furthermore, locating an individual character by its codepoint value is just as trivial:

```php
$ucd = new \UCD\Database();
$character = $ucd->locateCharacter(9731);
$codepoint = $character->getCodepoint();
echo $codepoint;

// outputting:
//  U+2603
```

### Executable

The primary intention of this project is to act as a library, however a small utility command is available for testing
and database generation/manipulation purposes. `bin/ucd search <codepoint>` will dump character information, and
`bin/ucd repository-transfer <from> <to>` will transfer characters from one repository implementation to another.
Please run `bin/ucd` for more detailed help.

## Properties

The intention of the make all character properties, as described in
[Unicode Standard Annex #44, Unicode Character Database - Properties](http://www.unicode.org/reports/tr44/) available
for interrogation. There are, however, a good quantity of them, so this remains work in progress. The following are
currently covered:

- [x] Name
- [x] Block
- [x] Age
- [x] General Category
- [x] Numeric Value
- [x] Numeric Type
- [x] Normalization
- [x] Canonical Combining Class
- [x] Decomposition Mapping
- [x] Decomposition Type
- [x] Join Control
- [x] Joining Group
- [x] Joining Type
- [x] Bidi Class
- [x] Bidi Control
- [x] Bidi Mirrored
- [x] Bidi Mirroring Glyph
- [x] Bidi Paired Bracket
- [x] Bidi Paired Bracket Type

## Tests

[PhpSpec](http://www.phpspec.net/) and [PHPUnit](https://phpunit.de/) backed integration tests are provided.
The easiest way to run them is via the Makefile; simply run `make test`.