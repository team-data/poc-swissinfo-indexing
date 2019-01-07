<?php

declare(strict_types=1);

namespace App\Model;

class HierarchyCluster
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var HierarchyCluster[]
     */
    public $children;

    /**
     * @var int
     */
    public $size;

    public function __construct(string $label)
    {
        $this->label = $label;
    }
}
