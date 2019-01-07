<?php

declare(strict_types=1);

namespace App\Model;

class Cluster
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var float
     */
    public $score;

    /**
     * @var string[]
     */
    public $ids;

    /**
     * @var bool
     */
    public $otherTopics = false;

    /**
     * @var int
     */
    public $value;

    public function __construct(string $label, float $score, array $ids, bool $otherTopics)
    {
        $this->label = $label;
        $this->score = $score;
        $this->ids = $ids;
        $this->otherTopics = $otherTopics;
        $this->value = \count($ids);
    }
}
