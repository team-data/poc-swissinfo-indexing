<?php

declare(strict_types=1);

namespace App\Util;

abstract class PageIDHelper
{
    private const PAGE_DETAIL_PREFIX = '/webservice/swi-eng-2.0/detail/';

    public static function extractIdFromPageDetailUrl(string $url): ?string
    {
        if (empty($url) || 0 !== mb_strpos($url, self::PAGE_DETAIL_PREFIX)) {
            return null;
        }

        return str_replace(self::PAGE_DETAIL_PREFIX, '', $url);
    }
}
