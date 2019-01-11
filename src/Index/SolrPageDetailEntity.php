<?php

declare(strict_types=1);

namespace App\Index;

use App\Model\SearchResult;
use Liip\SwissinfoClient\Model\PageDetail;
use Solarium\QueryType\Select\Result\Document as ResultDocument;
use Solarium\QueryType\Update\Query\Document\Document as UpdateDocument;

class SolrPageDetailEntity
{
    const SOLR_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

    public static function getSolrUpdateDocuments(PageDetail $pageDetail, string $id): UpdateDocument
    {
        $d = new UpdateDocument();
        $header = $pageDetail->getHeader();
        $d->addField('id', $id);
        $d->addField('language_s', $header->getLanguage());
        $d->addField('title_s', $header->getTitle());
        $d->addField('small_image_s', $header->getSmallImage());
        $d->addField('title_txt', $header->getTitle());
        $d->addField('date_date', self::getDate($header->getDate()));

        $d->addField('canonical_s', $header->getCanonical());

        $text = preg_replace('/\s+/', ' ', strip_tags($pageDetail->getHtmlDetail()));
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $d->addField('contents_txt_en', $text);

        return $d;
    }

    public static function buildSearchResult(ResultDocument $doc): SearchResult
    {
        $s = new SearchResult();
        $fields = $doc->getFields();

        $s->id = $fields['id'];
        $s->smallImage = $fields['small_image_s'] ?? '';
        $s->canonicalUrl = $fields['canonical_s'] ?? '';
        $s->language = $fields['language_s'];
        $s->title = $fields['title_s'];
        $s->date = $fields['date_date'];
        $s->score = $fields['score'];

        return $s;
    }

    public static function getSearchResultFields(): array
    {
        return [
            'id',
            'canonical_s',
            'title_s',
            'small_image_s',
            'date_date',
            'language_s',
            'score',
        ];
    }

    private static function getDate(\DateTimeInterface $date): string
    {
        /** @var \DateTimeInterface $input */
        $input = clone $date;

        return $input->format(self::SOLR_DATE_FORMAT);
    }
}
