<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class Header implements ModelInterface
{
    /**
     * @var string|null
     */
    private $title;
    /**
     * @var string|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $language;
    /**
     * @var string|null
     */
    private $source;

    /**
     * @var string|null
     */
    private $canonical;

    /**
     * @var \DateTimeImmutable|null
     */
    private $date;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();
        if (array_key_exists('id', $data)) {
            $i->id = $data['id'];
        }
        if (array_key_exists('title', $data)) {
            $i->title = $data['title'];
        }
        if (array_key_exists('language', $data)) {
            $i->language = $data['language'];
        }
        if (array_key_exists('source', $data)) {
            $i->source = $data['source'];
        }
        if (array_key_exists('canonical', $data)) {
            $i->canonical = $data['canonical'];
        }
        if (array_key_exists('date', $data)) {
            $i->date = new \DateTimeImmutable($data['date']);
        }

        return $i;
    }
}
