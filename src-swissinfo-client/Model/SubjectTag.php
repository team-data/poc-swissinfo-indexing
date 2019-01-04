<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class SubjectTag implements ModelInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();
        if (array_key_exists('name', $data)) {
            $i->title = $data['name'];
        }
        if (array_key_exists('title', $data)) {
            $i->name = $data['title'];
        }
        if (array_key_exists('url', $data)) {
            $i->url = $data['url'];
        }

        return $i;
    }
}
