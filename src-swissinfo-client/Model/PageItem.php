<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class PageItem implements ModelInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $canonical;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();

        $i->url = $data['url'] ?? '';
        $i->id = $data['id'] ?? '';

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
        if (array_key_exists('text', $data)) {
            $i->text = $data['text'];
        }
        if (array_key_exists('type', $data)) {
            $i->type = $data['type'];
        }

        return $i;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
