<?php

namespace App\Repositories\Database\Objects;

use App\Entities\Obj;

class ObjectsRepository
{

    public function getByKeys(array $keys): array
    {
         return Obj::query()
            ->whereIn(Obj::COLUMN_KEY, $keys)
            ->get()
            ->all();
    }

}
