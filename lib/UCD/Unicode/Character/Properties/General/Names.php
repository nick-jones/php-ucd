<?php

namespace UCD\Unicode\Character\Properties\General;

class Names
{
    /**
     * @var Name
     */
    private $primary;

    /**
     * @var Name[]
     */
    private $aliases;

    /**
     * @var Name
     */
    private $version1;

    /**
     * @param Name $primary
     * @param Name[] $aliases
     * @param Name $version1
     */
    public function __construct(Name $primary, array $aliases = [], Name $version1 = null)
    {
        $this->primary = $primary;
        $this->aliases = $aliases;
        $this->version1 = $version1;
    }

    /**
     * @return Name
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @return Name[]
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @return Name
     */
    public function getVersion1()
    {
        return $this->version1;
    }
}