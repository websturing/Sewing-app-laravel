<?php

namespace App\Repositories\Activity;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

interface ActivityRepositoryInterface
{
    public function query(): Builder;
    public function all(): Collection;
    public function getByUser(int $id): Collection;
}
