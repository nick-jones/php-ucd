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

The primary interface to utilise is `UCD\Collection`. This provides a number of methods to interrogate "codepoint assigned"
entities (i.e. `Character`, `NonCharacter`, and `Surrogate` instances) that reside within the UCD:

- `::getByCodepoint(Codepoint $codepoint)` - resolves a codepoint assigned entity
- `::getCharacterByCodepoint(Codepoint $codepoint)` - as above, but will only return `Character` instances
- `::onlyCharacters()` - returns a `Collection` instance containing only `Character` instances
- `::onlyNonCharacters()` - returns a `Collection` instance containing only `NonCharacter` instances
- `::onlySurrogates()` - returns a `Collection` instance containing only `Surrogate` instances
- `::filterWith(callable $filter)` - filters the `Collection`, using the return value of the supplied callable
- `::traverseWith(callable $filter)` - traverses the entire dataset, calling into the supplied callback with each entity

All but the codepoint resolving methods return an instance of `Collection`, allowing you to chain. It is likely that
you will want to leverage the default `Character\Repository` for resolution of characters, etc, in which case, calling
`UCD\Collection::fromFullDatabase()` will give you an instance backed by the `PHPFileRepository`. You can, of course,
leverage a different `Character\Repository` implementation.

Because this project makes good use of [generators](https://php.net/generators), the memory footprint of interrogating
the dataset is fairly nominal.

### Examples

#### Manual Filtering + Traversal

Say you wish to dump all characters that hold a numeric property and reside outside of the Basic Latin (ASCII) block. 
You could simply leverage the `::filterWith(callable $filter)` method, as described above, to interrogate the 
properties of each `Character` instance. You could then perhaps dump their latin equivalent representation by calling
`::getNumber()` on the `Numericity` property. For example:

```php
use UCD\Entity\Character;
use UCD\Entity\Character\Properties\General\Block;
use UCD\View\CharacterView;
use UCD\Collection;

$filter = function (Character $character) {
    $properties = $character->getProperties();
    $general = $properties->getGeneral();
    $block = $general->getBlock();

    return $properties->isNumeric()
        && !$block->equals(Block::fromValue(Block::BASIC_LATIN));
};

$dumper = function (Character $character) {
    $codepoint = $character->getCodepoint();
    $properties = $character->getProperties();
    $numerity = $properties->getNumericity();
    $number = $numerity->getNumber();
    $view = new CharacterView($character);
    $utf8 = $view->asUTF8();

    printf("%s: %s (~ %s)\n", $codepoint, $utf8, $number);
};

Collection::fromFullDatabase()
    ->onlyCharacters()
    ->filterWith($filter)
    ->traverseWith($dumper);

// outputting:
//  U+B2: ² (~ 2)
//  U+B3: ³ (~ 3)
//  U+B9: ¹ (~ 1)
//  U+BC: ¼ (~ 1/4)
//  U+BD: ½ (~ 1/2)
//  U+BE: ¾ (~ 3/4)
//  U+660: ٠ (~ 0)
//  U+661: ١ (~ 1)
//  U+662: ٢ (~ 2)
//  U+663: ٣ (~ 3)
//  <snip>
```

#### Codepoint Lookup

Locating an individual character by its codepoint value is trivial:

```php
use UCD\Collection;
use UCD\Entity\Codepoint;

$collection = Collection::fromFullDatabase();
$codepoint = Codepoint::fromInt(9731);
$character = $collection->getCharacterByCodepoint($codepoint);
$codepoint = $character->getCodepoint();

echo $codepoint;
```

#### Regex Building

A traverser is available to help build regular expressions based on codepoints within the `Collection`. For example,
if you wanted to produce a regular expression that matched numeric flavour bengali characters, then you could run
something along the lines of:

```php
use UCD\Entity\Character;
use UCD\Entity\Character\Properties\General\Block;
use UCD\Collection;
use UCD\Traverser\CodepointAggregator;
use UCD\Traverser\RegexBuilder;

$filter = function (Character $character) {
    $properties = $character->getProperties();
    $general = $properties->getGeneral();
    $block = $general->getBlock();

    return $properties->isNumeric()
        && $block->equals(Block::fromValue(Block::BENGALI));
};

$regexBuilder = new RegexBuilder(new CodepointAggregator());

Collection::fromFullDatabase()
    ->onlyCharacters()
    ->filterWith($filter)
    ->traverseWith($regexBuilder);

$cc = $regexBuilder->getCharacterClass();
$regex = sprintf('/^%s$/u', $cc);

var_dump($regex); // string(37) "/^[\x{9E6}-\x{9EF}\x{9F4}-\x{9F9}]$/u"
var_dump(preg_match($regex, '১')); // int(1)
var_dump(preg_match($regex, '1')); // int(0)
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