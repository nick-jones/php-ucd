<?php

namespace spec\UCD\SpecHelper;

use PhpSpec\Matcher\MatchersProviderInterface;

class Matchers implements MatchersProviderInterface
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
            'yieldFromIteratorAggregate' => function (\IteratorAggregate $aggregate, $key, $value) {
                $iterator = $aggregate->getIterator();
                $actualKey = $actualValue = null;
                foreach ($iterator as $actualKey => $actualValue) { break; }
                return $key === $actualKey && $value === $actualValue;
            },
            'beInvokable' => function ($subject) {
                return is_callable([$subject, '__invoke']);
            }
        ];
    }
}