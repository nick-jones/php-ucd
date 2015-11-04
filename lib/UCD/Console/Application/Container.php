<?php

namespace UCD\Console\Application;

use Pimple\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * @var array
     */
    private $prefixes = [];

    /**
     * {@inheritDoc}
     */
    public function offsetSet($id, $value)
    {
        parent::offsetSet($id, $value);

        $this->recordPrefix($id);
    }

    /**
     * @param string $id
     */
    private function recordPrefix($id)
    {
        $prefix = $this->extractPrefix($id);

        if (!$this->hasPrefix($prefix)) {
            $this->prefixes[$prefix] = [];
        }

        $this->prefixes[$prefix][$id] = true;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($id)
    {
        parent::offsetUnset($id);

        $prefix = $this->extractPrefix($id);

        if (isset($this->prefixes[$prefix][$id])) {
            unset($this->prefixes[$prefix][$id]);
        }
    }

    /**
     * @param string $prefix
     * @return bool
     */
    private function hasPrefix($prefix)
    {
        return array_key_exists($prefix, $this->prefixes);
    }

    /**
     * @param $id
     * @return mixed
     */
    private function extractPrefix($id)
    {
        $parts = explode('.', $id);

        return array_shift($parts);
    }

    /**
     * @param string $prefix
     * @return string[]
     */
    public function idsByPrefix($prefix)
    {
        if (!$this->hasPrefix($prefix)) {
            return [];
        }

        return array_keys($this->prefixes[$prefix]);
    }
}