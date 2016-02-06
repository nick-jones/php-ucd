# PHP UCD

[![Travis](https://img.shields.io/travis/nick-jones/php-ucd.svg?style=flat-square)](https://travis-ci.org/nick-jones/php-ucd)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/nick-jones/php-ucd.svg?style=flat-square)](https://scrutinizer-ci.com/g/nick-jones/php-ucd/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg?style=flat-square)](https://php.net/)

This project aims to present a PHP interface into the [Unicode Character Database](http://unicode.org/ucd/) (UCD).
It provides a means to lookup, filter, and interrogate the metadata & properties of unicode characters.

## Installation

You can install this library via [composer](http://getcomposer.org):

`composer require nick-jones/php-ucd`

## Usage

The primary interface to utilise is `UCD\Database`. This provides a number of methods to interrogate 
"codepoint assigned" entities (i.e. `Character`, `NonCharacter`, and `Surrogate` instances) that reside within the UCD:

- `Database::getByCodepoint(Codepoint $codepoint)` - resolves a codepoint assigned entity
- `Database::getCharacterByCodepoint(Codepoint $codepoint)` - as above, but will only return `Character` instances
- `Database::getByCodepoints(Codepoint\Collection $codepoints)` - resolves multiple codepoint assigned entities
- `Database::getCodepointsByBlock(Block $block)` - resolves codepoints residing in the supplied block
- `Database::getByBlock(Block $block)` - resolves codepoint assigned entities residing in the supplied block
- `Database::getCodepointsByCategory(GeneralCategory $category)` - resolves codepoints residing in the supplied category
- `Database::getByCategory(GeneralCategory $category)` - resolves codepoint assigned entities residing in the supplied category
- `Database::all()` - returns a `Collection` instance containing everything assigned a codepoint within the database
- `Database::onlyCharacters()` - returns a `Collection` instance containing only `Character` instances
- `Database::onlyNonCharacters()` - returns a `Collection` instance containing only `NonCharacter` instances
- `Database::onlySurrogates()` - returns a `Collection` instance containing only `Surrogate` instances

The `UCD\Unicode\Character\Collection` class, returned by a number of methods, provides methods for filtering,
traversal, codepoint extractions, amongst other things.

It is likely that you will want to leverage the default `Character\Repository` for resolution of characters, etc, in
which case, calling `UCD\Collection::fromDisk()` will give you an instance backed by `FileRepository`. You can,
of course, leverage a different `Character\Repository` implementation, if you so wish, by providing it to the
constructor of `UCD\Database`.

Because this project makes good use of [generators](https://php.net/generators), the memory footprint of interrogating
the dataset is fairly nominal.

## Caveats

As of Unicode 8.0 there are > 260,000 items assigned codepoints. Reading, filtering and traversing all of these will
take a few seconds. As such, if your intention is to identify items by filtering rules, you would be well advised to
cache the output in some suitable form (e.g. build a regex, or PHP array of codepoints) which can then be interrogated,
rather than always returning to filter and traverse the database. If your intention is to perform lookup by codepoint,
then it is no problem to call into this library when and as required, as this is an efficient operation.

## Examples

### Manual Filtering + Traversal

Say you wish to dump all characters that hold a numeric property and reside outside of the Basic Latin (ASCII) block. 
You could simply leverage the `Collection::filterWith(callable $filter)` method to interrogate the properties of each
`Character` instance. You could then perhaps dump their latin equivalent representation by calling `::getNumber()` on
the `Numericity` property. For example:

```php
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;
use UCD\View\CharacterView;
use UCD\Database;

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

Database::fromDisk()
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

### Codepoint Lookup

Locating an individual character by its codepoint value is trivial:

```php
use UCD\Database;
use UCD\Unicode\Codepoint;

$collection = Database::fromDisk();
$codepoint = Codepoint::fromInt(9731);
// ..or $codepoint = Codepoint::fromHex('2603');
// ..or $codepoint = Codepoint::fromUTF8('☃');
$character = $collection->getCharacterByCodepoint($codepoint);
$properties = $character->getProperties();
$general = $properties->getGeneral();
$names = $general->getNames();

// prints "U+2603: SNOWMAN"
printf("%s: %s\n", $character->getCodepoint(), $names->getPrimary());
```

### Regex Building

The library provides a means to produce regular expression characters classes based codepoints that have been
extracted or aggregated from a character collection. For example, if you wanted to produce a regular expression
that matched numeric flavour bengali characters, then you could use something along the lines of:

```php
use UCD\Database;
use UCD\Unicode\Character;
use UCD\Unicode\Character\Properties\General\Block;

$filter = function (Character $character) {
    $properties = $character->getProperties();
    $general = $properties->getGeneral();
    $block = $general->getBlock();

    return $properties->isNumeric()
        && $block->equals(Block::fromValue(Block::BENGALI));
};

$cc = Database::fromDisk()
    ->onlyCharacters()
    ->filterWith($filter)
    ->extractCodepoints()
    ->aggregate()
    ->toRegexCharacterClass();

$regex = sprintf('/^%s$/u', $cc);

var_dump($regex); // string(37) "/^[\x{9E6}-\x{9EF}\x{9F4}-\x{9F9}]$/u"
var_dump(preg_match($regex, '১')); // int(1)
var_dump(preg_match($regex, '1')); // int(0)
```

## Executable

The primary intention of this project is to act as a library, however a small utility command is available for testing
and database generation/manipulation purposes. `bin/ucd search <codepoint>` will dump character information, and
`bin/ucd repository-transfer <from> <to>` will transfer characters from one repository implementation to another.
Please run `bin/ucd` for more detailed help.

## Properties

The intention of the most interesting of the available character properties, as described in
[Unicode Standard Annex #44, Unicode Character Database - Properties](http://www.unicode.org/reports/tr44/), available
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