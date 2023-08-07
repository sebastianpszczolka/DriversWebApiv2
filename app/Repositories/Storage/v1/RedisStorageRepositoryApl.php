<?php

namespace App\Repositories\Storage\v1;

use App\Http\Requests\Installations\v1\Storage\ReadAplRequest;
use App\Http\Requests\Installations\v1\Storage\WriteAplRequest;
use Illuminate\Support\Facades\Redis;

class RedisStorageRepositoryApl implements StorageRepositoryApl
{
    private const KEY_STREAM = 'sysStreamInp';

    public function readApl(ReadAplRequest $data, int $node): array
    {
        $result = [];

        if (empty($data->members)) {

            foreach ($data->variables as $key) {
                $key = $node . $key;
                $result[$key] = Redis::hGetAll($key);
            }
        } else {

            foreach ($data->variables as $key) {
                $key = $node . $key;
                $result[$key] = array_intersect_key(Redis::hGetAll($key), array_flip($data->members));
            }
        }

        return $result;
    }

    public function writeApl(WriteAplRequest $data, int $node): array
    {
        Redis::pipeline(function ($pipe) use (&$data, $node) {
            foreach ($data->variables as $key => $value) {
                $key = $node . $key;
                if (is_array($value)) {
                    $pipe->hMSet($key, $value);
                } else {
                    $pipe->Set($key, $value);
                }

                if ($data->expired)
                    $pipe->expire($key, $data->expired);
            }
        });

        foreach ($data->variables as $key => $value) {
            $listPath = Redis::hGet($node . $key, RedisStorageRepositoryApl::KEY_STREAM);
            if (!empty($listPath)) {
                Redis::lPush($node . $listPath, json_encode([$key => ['value' => $value, 'time' => time()]]));
            }
        }

        return [];
    }
}
