<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class PageDetail implements ModelInterface
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @var string|null
     */
    private $htmlDetail;

    /**
     * @var Footer
     */
    private $footer;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();
        $i->header = Header::create($data['header'] ?? []);
        $i->footer = Footer::create($data['footer'] ?? []);
        $i->htmlDetail = $data['htmldetail'] ?? null;

        return $i;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getHtmlDetail(): ?string
    {
        return $this->htmlDetail;
    }

    public function getFooter(): Footer
    {
        return $this->footer;
    }
}
