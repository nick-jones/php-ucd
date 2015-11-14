<?php

namespace UCD\Entity\Collection;

use UCD\Entity\Collection;

interface SnapshotCapableCollection extends Collection
{
    /**
     * @return static
     */
    public function takeSnapshot();
}