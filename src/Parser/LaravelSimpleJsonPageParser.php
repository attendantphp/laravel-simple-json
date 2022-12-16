<?php

namespace Attendant\Laravel\SimpleJson\Parser;

use Attendant\Core\Parser\PageParser;

class LaravelSimpleJsonPageParser extends LaravelSimpleJsonCollectionParser implements PageParser
{
    public function page(): int|string
    {
        return $this->request->input('page')['number'] ?? 1;
    }

    public function perPage(): int
    {
        return $this->request->input('page')['count'] ?? 50;
    }
}