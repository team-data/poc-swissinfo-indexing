<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class Header implements ModelInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
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
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var SubjectTag[]
     */
    private $subjectTags = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();
        $i->id = $data['id'] ?? '';
        $i->title = $data['title'] ?? '';
        $i->date = new \DateTimeImmutable($data['date'] ?? 'now');

        if (array_key_exists('language', $data)) {
            $i->language = $data['language'];
        }
        if (array_key_exists('source', $data)) {
            $i->source = $data['source'];
        }
        if (array_key_exists('canonical', $data)) {
            $i->canonical = $data['canonical'];
        }
        if (array_key_exists('canonicalUrl', $data)) {
            $i->canonical = $data['canonicalUrl'];
        }

        if (\is_array($data['subjectTags'] ?? null)) {
            foreach ($data['subjectTags'] as $tagData) {
                $i->subjectTags[] = SubjectTag::create($tagData);
            }
        }

        return $i;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return SubjectTag[]
     */
    public function getSubjectTags(): array
    {
        return $this->subjectTags;
    }
}
