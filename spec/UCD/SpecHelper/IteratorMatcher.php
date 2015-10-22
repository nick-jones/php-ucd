<?php

namespace spec\UCD\SpecHelper;

use PhpSpec\Matcher\MatchersProviderInterface;

class IteratorMatcher implements MatchersProviderInterface
{
    /**
     * @return array
     */
    public function getMatchers()
    {
        return [
            'iterateLike' => function ($subject, $expected) {
                return $expected == iterator_to_array($subject);
            },
            'beInvokable' => function ($subject) {
                return is_callable([$subject, '__invoke']);
            }
        ];
    }
}