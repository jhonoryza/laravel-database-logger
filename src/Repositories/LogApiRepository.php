<?php

namespace Jhonoryza\DatabaseLogger\Repositories;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Jhonoryza\DatabaseLogger\Models\LogApi;

class LogApiRepository
{
    public static function getCursorList(?int $perPage): CursorPaginator
    {
        $perPage = $perPage ?? 10;

        return LogApi::query()
            ->cursorPaginate(perPage: $perPage, cursorName: 'page');
    }

    public static function getPaginateList(?int $perPage): LengthAwarePaginator
    {
        $perPage = $perPage ?? 10;

        return LogApi::query()
            ->paginate(perPage: $perPage, pageName: 'page');
    }

    public static function getSimplePaginateList(?int $perPage): Paginator
    {
        $perPage = $perPage ?? 10;

        return LogApi::query()
            ->simplePaginate(perPage: $perPage, pageName: 'page');
    }

    public static function getAllList(?int $limit): Collection
    {
        return LogApi::query()
            ->when(value: $limit, callback: fn ($query, $value) => $query->limit($value))
            ->get();
    }

    public static function getDetail(int $id): Model
    {
        return LogApi::findOrFail($id);
    }

    public static function create(array $data): LogApi
    {
        return LogApi::create([
            'url' => $data['url'],
            'method' => $data['method'],
            'code' => $data['code'],
            'header' => $data['header'],
            'payload' => $data['payload'],
            'response' => $data['response'],
        ]);
    }
}
