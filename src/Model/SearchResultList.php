<?php

declare(strict_types=1);

namespace App\Model;

class SearchResultList
{
    /**
     * @var int
     */
    public $numFound;

    /**
     * @var SearchResult[]
     */
    public $items;
}
