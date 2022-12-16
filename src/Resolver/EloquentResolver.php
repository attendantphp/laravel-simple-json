<?php

namespace Attendant\Laravel\SimpleJson\Resolver;

use Attendant\Core\Definition\Field\Field;
use Attendant\Core\Parser\CollectionParser;
use Attendant\Core\Parser\OneParser;
use Attendant\Core\Parser\PageParser;
use Attendant\Core\Resolver\Resolver;
use Attendant\Core\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class EloquentResolver implements Resolver
{
    public function __construct(private readonly EloquentTypeResolver $resolver)
    {}

    public function collection(Resource $resource, CollectionParser $parser): Collection
    {
        /** @var class-string<Model> $model */
        $model = $this->resolver->getModelClass($resource->getType());
        $table = (new $model)->getTable();

        $fields = $parser->fields()->map(
            fn (Field $field) => "{$table}.{$field->getColumnName()}"
        );

        return $model::query()
            ->select($fields->toArray())
            ->get();
    }

    public function one(Resource $resource, OneParser $parser): mixed
    {
        /** @var class-string<Model> $model */
        $model = $this->resolver->getModelClass($resource->getType());

        return $model::query()->findOrFail($parser->id());
    }

    public function page(Resource $resource, PageParser $parser): mixed
    {
        /** @var class-string<Model> $model */
        $model = $this->resolver->getModelClass($resource->getType());
        $table = (new $model)->getTable();

        $fields = $parser->fields()->map(
            fn (Field $field) => "{$table}.{$field->getColumnName()}"
        );

        return $model::query()
             ->select($fields->toArray())
             ->paginate(
                 perPage: $parser->perPage(),
                 page: $parser->page()
             );
    }
}