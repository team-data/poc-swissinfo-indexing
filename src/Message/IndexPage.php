<?php

declare(strict_types=1);

namespace App\Message;

class IndexPage
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $recursiveIndexing;

    public function __construct(string $id, bool $recursiveIndexing = false)
    {
        $this->id = $id;
        $this->recursiveIndexing = $recursiveIndexing;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isRecursiveIndexing(): bool
    {
        return $this->recursiveIndexing;
    }
}
