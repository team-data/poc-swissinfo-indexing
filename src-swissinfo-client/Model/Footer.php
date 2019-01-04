<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class Footer implements ModelInterface
{
    /**
     * @var PageItem[]
     */
    private $related = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();

        if (\is_array($data['related'] ?? null)) {
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
