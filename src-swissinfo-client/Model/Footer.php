<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class Footer implements ModelInterface
{
    /**
     * @var PageItem[]
     */
    private $related;

    private function __construct()
    {
        $this->related = [];
    }

    public static function create(array $data): self
    {
        $i = new self();

        if (array_key_exists('related', $data)) {
            foreach ($data['related'] as $related) {
                $i->related[] = PageItem::create($related);
            }
        }

        return $i;
    }

    /**
     * @return PageItem[]
     */
    public function getRelated(): array
    {
        return $this->related;
    }
}
