<?php

namespace UCD\Unicode\Collection;

use UCD\Unicode\Collection;

interface SnapshotCapableCollection extends Collection
{
    /**
     * @return static
     */
    public function takeSnapshot();
}