<?php

namespace Attendant\Laravel\SimpleJson\Formatter;

use Attendant\Core\Formatter\Formatter;
use Attendant\Core\Resource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use UnexpectedValueException;

final class LaravelSimpleJsonFormatter implements Formatter
{
    public function __construct(
        private readonly Request $request,
        private readonly ResponseFactory $responseFactory
    ) {}

    public function collection(Resource $resource, Collection $collection): JsonResponse
    {
        return $this->responseFactory->json([
            'type' => $resource->getType(),
            'data' => $collection->toArray(),
        ]);
    }

    public function one(Resource $resource, mixed $entity): JsonResponse
    {
        if (! $entity instanceof Model) {
            throw new UnexpectedValueException;
        }

        return $this->responseFactory->json([
            'type' => $resource->getType(),
            'data' => $entity->toArray(),
        ]);
    }

    public function page(Resource $resource, mixed $page): mixed
    {
        if (! $page instanceof LengthAwarePaginator) {
            throw new UnexpectedValueException;
        }

        $currentPage = $page->currentPage();
        $previousPageUrl = null;
        $nextPageUrl = null;

        if ($currentPage > 1) {
            $previousPageUrl = $this->request->fullUrlWithQuery([
                'page[number]' => $currentPage - 1,
            ]);
        }

        if ($currentPage < $page->lastPage()) {
            $nextPageUrl = $this->request->fullUrlWithQuery([
                'page[number]' => $currentPage + 1,
            ]);
        }

        return $this->responseFactory->json([
            'type' => $resource->getType(),
            'data' => $page->items(),
            'meta' => [
                'page' => [
                    'perPage' => $page->perPage(),
                    'previousPageUrl' => $previousPageUrl,
                    'nextPageUrl' => $nextPageUrl,
                ],
            ]
        ]);
    }
}