<?php

namespace spec\UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpSpec\ObjectBehavior;
use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\Property;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFile;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\PropertyFiles;

/**
 * @mixin PropertyFiles
 */
class PropertyFilesSpec extends ObjectBehavior
{
    public function it_can_resolve_a_property_file_for_a_given_property(Property $property, PropertyFile $file)
    {
        $property->__toString()
            ->willReturn('x');

        $file->getProperty()
            ->willReturn($property);

        $this->givenCollectionContainsFile($file);

        $this->getByProperty($property)
            ->shouldReturn($file);
    }

    public function it_throws_UnexpectedValueException_if_no_file_exists_for_a_supplied_property(Property $property)
    {
        $property->__toString()
            ->willReturn('x');

        $this->shouldThrow(UnexpectedValueException::class)
            ->duringGetByProperty($property);
    }

    private function givenCollectionContainsFile(PropertyFile $file)
    {
        $this->add($file);
    }
}