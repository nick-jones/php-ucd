<?php

namespace UCD\Unicode\Character\Repository;

use UCD\Exception;
use UCD\Unicode\Character\Properties\General\GeneralCategory;

class GeneralCategoryNotFoundException extends Exception
{
    /**
     * @var GeneralCategory
     */
    protected $category;

    /**
     * @return GeneralCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param GeneralCategory $category
     * @return self
     */
    public static function withCategory(GeneralCategory $category)
    {
        $exception = new self();
        $exception->category = $category;

        return $exception;
    }
}