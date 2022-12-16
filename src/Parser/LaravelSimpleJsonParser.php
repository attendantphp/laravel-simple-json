<?php

namespace Attendant\Laravel\SimpleJson\Parser;

use Attendant\Core\Parser\CollectionParser;
use Attendant\Core\Parser\OneParser;
use Attendant\Core\Parser\PageParser;
use Attendant\Core\Parser\Parser;
use Attendant\Core\Resource;
use Illuminate\Http\Request;

final class LaravelSimpleJsonParser implements Parser
{
    public function __construct(private readonly Request $request)
    {}

    public function collection(Resource $resource): CollectionParser
    {
        return new LaravelSimpleJsonCollectionParser($this->request, $resource);
    }

    public function one(Resource $resource): OneParser
    {
        return new LaravelSimpleJsonOneParser($this->request, $resource);
    }

    public function page(Resource $resource): PageParser
    {
        return new LaravelSimpleJsonPageParser($this->request, $resource);
    }
}