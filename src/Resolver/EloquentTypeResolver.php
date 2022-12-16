<?php

namespace Attendant\Laravel\SimpleJson\Resolver;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class EloquentTypeResolver
{
    private array $map = [];

    public function __construct(array $map)
    {
        $this->setMap($map);
    }

    /**
     * @return class-string<Model>
     */
    public function getModelClass(string $type): string
    {
        return $this->map[$type];
    }

    private function setMap(array $map): void
    {
        foreach ($map as $type => $model) {
            if (! is_subclass_of($model, Model::class)) {
                throw new InvalidArgumentException;
            }

            $this->map[$type] = $model;
        }
    }
}