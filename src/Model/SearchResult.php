<?php

declare(strict_types=1);

namespace App\Model;

class SearchResult
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $canonicalUrl;

    /**
     * @var float
     */
    public $score;

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $date;
}
