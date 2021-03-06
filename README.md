# PHP UCD

[![Travis](https://img.shields.io/travis/nick-jones/php-ucd.svg?style=flat-square)](https://travis-ci.org/nick-jones/php-ucd)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/nick-jones/php-ucd.svg?style=flat-square)](https://scrutinizer-ci.com/g/nick-jones/php-ucd/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg?style=flat-square)](https://php.net/)

This project aims to present a PHP interface into the [Unicode Character Database](http://unicode.org/ucd/) (UCD).
It provides a means to lookup, filter, and interrogate the metadata & properties of unicode characters.

## Installation

You can install this [library](https://packagist.org/packages/nick-jones/php-ucd) via [composer](http://getcomposer.org):

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
- `Database::getCodepointsByScript(Script $script)` - resolves codepoints residing in the supplied script
- `Database::getByScript(Script $script)` - resolves codepoint assigned entities residing in the supplied script
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
    $utf8 = $codepoint->toUTF8();

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

$database = Database::fromDisk();
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

It is just as trivial to interrogate multiple codepoints. For example, you could print the name of every codepoint
residing within a string:

```php
use UCD\Database;
use UCD\Unicode\Codepoint;

$database = Database::fromDisk();
$string = 'abc';
$codepoints = Codepoint\Collection::fromUTF8($string);
$assigned = $database->getByCodepoints($codepoints);

foreach ($assigned->getCharacters() as $character) {
    $properties = $character->getProperties();
    $general = $properties->getGeneral();
    $names = $general->getNames();

    printf("%s: %s\n", $character->getCodepoint(), $names->getPrimary());
}

// outputting:
//  U+61: LATIN SMALL LETTER A
//  U+62: LATIN SMALL LETTER B
//  U+63: LATIN SMALL LETTER C
```

Factory methods are available on the `Codepoint` and `Codepoint\Collection` classes to construct instances based on UTF-8,
UTF-16BE, UTF-16LE, UTF-32BE and UTF-32LE encoded character(s).

### Regex Building

The library provides a means to build regular expression characters classes based codepoints that have been
extracted or aggregated from a collection of characters. For example, if you wanted to produce a regular expression
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

### Map Building

This library can be used for building maps for various purposes. One such example is building a lowercase → uppercase
character map. This is relatively simple to achieve; interrogate the properties of each character to check whether
a mapping to a different character exists - if one does, print it out in PHP syntax:

```php
use UCD\Database;

$characters = Database::fromDisk()
    ->onlyCharacters();

echo 'static $map = [' . PHP_EOL;

foreach ($characters as $character) {
    $codepoint = $character->getCodepoint();
    $properties = $character->getProperties();
    $case = $properties->getLetterCase();
    $mappings = $case->getMappings();
    $upperMapping = $mappings->getUppercase();
    $upper = $upperMapping->getSimple();

    if (!$upper->equals($codepoint)) {
        $from = $codepoint->toUnicodeEscape();
        $to = $upper->toUnicodeEscape();
        printf('    "%s" => "%s",', $from, $to);
        echo PHP_EOL;
    }
}

echo '];';

// outputting:
//  static $map = [
//      "\u{61}" => "\u{41}",
//      "\u{62}" => "\u{42}",
//      "\u{63}" => "\u{43}",
//      <snip>
//      "\u{1E942}" => "\u{1E920}",
//      "\u{1E943}" => "\u{1E921}",
//  ];
```

This can then be leveraged as follow:

```php
$lower = 'aς!';
$upper = '';

for ($i = 0; $i < mb_strlen($lower); $i++) {
    $char = mb_substr($lower, $i, 1);
    $upper .= $map[$char] ?? $char;
}

var_dump($upper); // string(4) "AΣ!"
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

[PhpSpec](http://www.phpspec.net/) class specifications and [PHPUnit](https://phpunit.de/) backed integration tests are 
provided. The easiest way to run them is via the Makefile; simply run `make test`.