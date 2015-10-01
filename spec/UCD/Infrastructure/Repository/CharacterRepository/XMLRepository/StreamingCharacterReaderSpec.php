<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

use PhpSpec\ObjectBehavior;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\StreamingCharacterReader;
use UCD\Infrastructure\Repository\CharacterRepository\XMLRepository\XMLReader;
use VirtualFileSystem\FileSystem;

/**
 * @mixin StreamingCharacterReader
 */
class StreamingCharacterReaderSpec extends ObjectBehavior
{
    const XML_DATA = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char cp="0000"/>
      <char cp="0001"/>
      <foob cp="0002"/>
   </repertoire>
</ucd>
XML;

    public function it_returns_all_character_elements()
    {
        $fs = new FileSystem();
        $path = $fs->path('/ucd.xml');
        file_put_contents($path, self::XML_DATA);

        $xmlReader = new XMLReader($path);

        $this->beConstructedWith($xmlReader);

        $this->read()
            ->shouldIterateWithCharacterElements(['0000', '0001']);
    }

    public function getMatchers()
    {
        return [
            'iterateWithCharacterElements' => function ($subject, array $codepoints) {
                /** @var \DOMElement $element */
                foreach ($subject as $i => $element) {
                    if ($element->tagName !== 'char' || $element->getAttribute('cp') !== array_shift($codepoints)) {
                        return false;
                    }
                }
                return count($codepoints) === 0;
            }
        ];
    }
}