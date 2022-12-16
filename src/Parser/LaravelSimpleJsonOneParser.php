<?php

namespace Attendant\Laravel\SimpleJson\Parser;

use Attendant\Core\Parser\OneParser;
use Attendant\Core\Resource;
use Illuminate\Http\Request;

class LaravelSimpleJsonOneParser implements OneParser
{
    public function __construct(private readonly Request $request, private readonly Resource $resource)
    {
        //
    }

    public function id(): mixed
    {
        return $this->request->route($this->resource->getType());
    }
}