<?php

namespace integration\UCD\CharacterRepository;

use integration\UCD\TestCase as BaseTestCase;

use UCD\Entity\Character;
use UCD\Entity\Codepoint;
use UCD\Entity\Character\Repository;
use UCD\Entity\Character\Repository\CharacterNotFoundException;
use UCD\Entity\Character\WritableRepository;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

abstract class TestCase extends BaseTestCase
{
    /**
     * Tests extending this must set this property. The Repository instance must hold a single
     * character assigned to codepoint U+0. Since all repositories should behave the same, there
     * isn't any great reason why additional tests be covered in addition to these.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * @test
     */
    public function it_can_locate_a_held_character_by_codepoint()
    {
        $codepoint = Codepoint::fromInt(0);
        $character = $this->repository->getByCodepoint($codepoint);
        $codepoint = $character->getCodepoint();

        ha::assertThat('character', $character, hm::is(hm::anInstanceOf(Character::class)));
        ha::assertThat('codepoint', $codepoint->getValue(), hm::is(hm::identicalTo(0)));
    }

    /**
     * @test
     */
    public function it_throws_CharacterNotFoundException_if_a_character_is_not_held()
    {
        $codepoint = Codepoint::fromInt(1);

        try {
            $this->repository->getByCodepoint($codepoint);
            $this->fail('Expected CharacterNotFoundException to be thrown');
        } catch (CharacterNotFoundException $e) {
            ha::assertThat($codepoint->equals($e->getCodepoint()));
        }
    }

    /**
     * @test
     */
    public function it_can_provide_all_held_characters()
    {
        $characters = $this->repository->getAll();
        $tally = 0;

        foreach ($characters as $character) {
            $tally++;
            ha::assertThat('character', $character, hm::is(hm::anInstanceOf(Character::class)));
        }

        ha::assertThat('count', $tally, hm::is(hm::equalTo(1)));
    }

    /**
     * @test
     */
    public function it_provides_a_count_of_held_characters()
    {
        $count = count($this->repository);

        ha::assertThat('count', $count, hm::is(hm::identicalTo(1)));
    }

    /**
     * @test
     */
    public function it_can_be_added_to_if_writable()
    {
        if (!$this->repository instanceof WritableRepository) {
            $this->markTestSkipped('Repository is not writable');
        }

        $codepoint = Codepoint::fromInt(1);
        $addCharacter = $this->buildCharacterWithCodepoint($codepoint);

        $this->repository->addMany([$addCharacter]);
        $character = $this->repository->getByCodepoint($codepoint);

        ha::assertThat('character', $character, hm::is(hm::equalTo($addCharacter)));
        ha::assertThat('count', count($this->repository), hm::is(hm::equalTo(2)));
    }
}