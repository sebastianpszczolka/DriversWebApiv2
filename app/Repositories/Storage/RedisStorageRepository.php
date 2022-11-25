<?php

namespace App\Repositories\Storage;

use App\Domain\Common\CommandsStorageRedis;
use App\Domain\Service\StorageRedisDevice;
use App\Dto\Storage\ReadMsgBoxDstDto;
use App\Dto\Storage\ReadMsgBoxDto;
use App\Dto\Storage\ReadMsgBoxSrcDto;
use App\Dto\Storage\WriteDeviceDto;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Http\Requests\Storage\WriteDeviceRequest;
use Illuminate\Support\Facades\Redis;

class RedisStorageRepository implements StorageRepository
{

    private const KEY_DEVICE = '/Device/';
    private const KEY_TIMESTAMP = 'timestamp';

    public function readApl(array $data): array
    {
        // TODO: Implement readApl() method.
        return [];
    }

    public function writeApl(array $data): array
    {
        // TODO: Implement writeApl() method.
        return [];
    }

    public function readDev(array $data): array
    {
        // TODO: Implement readDev() method.
        return [];
    }

    public function writeDev(WriteDeviceRequest $data): WriteDeviceDto
    {
        Redis::pipeline(function ($pipe) use (&$data) {
            $pipe->hMSet($data->Src->install . RedisStorageRepository::KEY_DEVICE . $data->Src->Node, [RedisStorageRepository::KEY_TIMESTAMP => time()]);
            foreach ($data->var as $key => $set) {
                $key = $data->Src->install . $data->Dst->mount . $key;

                if (is_array($set)) {
                    $set[RedisStorageRepository::KEY_TIMESTAMP] = time();
                    $pipe->hMSet($key, $set);
                } else {
                    $pipe->Set($key, $set);
                }
            }
        });

        return new WriteDeviceDto([
            'Src' => ['Time' => time(), 'install' => $data->Src->install],
            'Dst' => ['Node' => $data->Dst->Node, 'install' => $data->Dst->install]
        ]);

    }

    public function readMsgBox(ReadMsgBoxRequest $data): ReadMsgBoxDto
    {
        $inpBox = [];
        foreach ($data->InpBox as $key) {
            $inpBox[$key] = [];
            while (true) {
                $el = Redis::lPop($data->Src->install . $key);
                if ($el === false)
                    break;

                $inpBox[$key][] = json_decode($el);
            }
        }

        return new ReadMsgBoxDto(['InpBox' => $inpBox,
            'Src' => ['Time' => time(), 'install' => $data->Src->install],
            'Dst' => ['Node' => $data->Dst->Node, 'install' => $data->Dst->install]
        ]);
    }
}
