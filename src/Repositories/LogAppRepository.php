<?php

namespace Jhonoryza\DatabaseLogger\Repositories;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Jhonoryza\DatabaseLogger\Models\LogApp;

class LogAppRepository
{
    public function getCursorList(?int $perPage): CursorPaginator
    {
        $perPage = $perPage ?? 10;

        return LogApp::query()
            ->cursorPaginate(perPage: $perPage, cursorName: 'page');
    }

    public function getPaginateList(?int $perPage): LengthAwarePaginator
    {
        $perPage = $perPage ?? 10;

        return LogApp::query()
            ->paginate(perPage: $perPage, pageName: 'page');
    }

    public function getSimplePaginateList(?int $perPage): Paginator
    {
        $perPage = $perPage ?? 10;

        return LogApp::query()
            ->simplePaginate(perPage: $perPage, pageName: 'page');
    }

    public function getAllList(?int $limit): Collection
    {
        return LogApp::query()
            ->when(value: $limit, callback: fn ($query, $value) => $query->limit($value))
            ->get();
    }

    public function getDetail(int $id): Model
    {
        return LogApp::findOrFail($id);
    }
}
