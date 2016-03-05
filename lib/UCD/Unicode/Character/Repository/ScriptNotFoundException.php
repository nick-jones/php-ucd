<?php

namespace UCD\Unicode\Character\Repository;

use UCD\Exception;
use UCD\Unicode\Character\Properties\General\Script;

class ScriptNotFoundException extends Exception
{
    /**
     * @var Script
     */
    protected $script;

    /**
     * @return Script
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param Script $script
     * @return self
     */
    public static function withScript(Script $script)
    {
        $exception = new self();
        $exception->script = $script;

        return $exception;
    }
}