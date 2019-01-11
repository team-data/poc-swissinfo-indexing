<?php

declare(strict_types=1);

namespace App\Model;

class SearchMLTResultList extends SearchResultList
{
    /**
     * @var string[]
     */
    public $interestingTerms = [];

    /**
     * @var string
     */
    public $originalSearch;
}
