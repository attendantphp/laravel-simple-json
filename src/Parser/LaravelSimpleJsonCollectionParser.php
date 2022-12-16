<?php

namespace Attendant\Laravel\SimpleJson\Parser;

use Attendant\Core\Definition\Field\Field;
use Attendant\Core\Parser\CollectionParser;
use Attendant\Core\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LaravelSimpleJsonCollectionParser implements CollectionParser
{
    protected Collection $fields;

    public function __construct(protected readonly Request $request, protected readonly Resource $resource)
    {
        $this->resolveFields();
    }

    public function fields(): Collection
    {
        return $this->fields;
    }

    public function sorts(): Collection
    {
        // TODO: Implement sorts() method.
    }

    private function resolveFields()
    {
        $fields = collect($this->request->input('fields'))
            ->mapWithKeys(function ($fields, $key) {
                return collect(explode(',', $fields))
                    ->filter()
                    ->unique()
                    ->toArray();
            });

        if (! $fields->has($this->resource->getType())) {
            $this->fields = $this->resource->getFields();

            return;
        }

        $allowedFields = $this->resource->getFields()->mapWithKeys(
            fn (Field $field) => [$field->getQueryName() => $field]
        );

        $this->fields = $fields->map(
            fn ($field) => $allowedFields->get($field) ?? throw new HttpException(
                statusCode: 400,
                message: "Unknown field [$field]."
            )
        );
    }
}