<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Model;

class PageDetail implements ModelInterface
{
    /**
     * @var Header|null
     */
    private $header;

    /**
     * @var string|null
     */
    private $htmlDetail;

    /**
     * @var Footer|null
     */
    private $footer;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $i = new self();
        if (array_key_exists('header', $data)) {
            $i->header = Header::create($data['header']);
        }
        if (array_key_exists('htmldetail', $data)) {
            $i->htmlDetail = $data['htmldetail'];
        }
        if (array_key_exists('footer', $data)) {
            $i->footer = Footer::create($data['footer']);
        }

        return $i;
    }

    public function getHeader(): ?Header
    {
        return $this->header;
    }

    public function getHtmlDetail(): ?string
    {
        return $this->htmlDetail;
    }

    public function getFooter(): ?Footer
    {
        return $this->footer;
    }
}
