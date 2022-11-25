<?php

namespace App\Repositories\Storage;

use App\Domain\Common\CommandsStorageRedis;
use App\Dto\Storage\ReadMsgBoxDstDto;
use App\Dto\Storage\ReadMsgBoxDto;
use App\Dto\Storage\ReadMsgBoxSrcDto;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Http\Requests\Storage\WriteDeviceRequest;
use Illuminate\Support\Facades\Redis;

class RedisStorageRepository implements StorageRepository
{

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

    public function writeDev(WriteDeviceRequest $data): array
    {
        // TODO: Implement writeDev() method.
        return ['empty'];
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
