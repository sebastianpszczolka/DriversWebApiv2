<?php
declare(strict_types=1);

namespace App\Repositories\Storage;

use App\Dto\Storage\ReadDeviceDto;
use App\Dto\Storage\ReadMsgBoxDto;
use App\Dto\Storage\WriteDeviceDto;
use App\Http\Requests\Storage\ReadDeviceRequest;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Http\Requests\Storage\WriteDeviceRequest;
use App\Loggers\DefaultLogger;
use Generator;
use Illuminate\Support\Facades\Redis;

class RedisStorageRepository implements StorageRepository
{
    private DefaultLogger $logger;
    private const KEY_DEVICE = '/Device/';
    private const KEY_TIMESTAMP = 'timestamp';
    private const KEY_STREAM = 'sysStreamInp';
    private const KEY_VALUE = 'val';
    private const EXPIRATION_TIME = 86400;

    public function __construct(DefaultLogger $logger)
    {
        $this->logger = $logger;
    }

    public function readApl(array $data): array
    {
        $result = [];

        foreach ($data as $key) {
            $result[$key] = Redis::hGetAll($key);
        }
        return $result;
    }

    public function writeApl(array $data): array
    {
        Redis::pipeline(function ($pipe) use (&$data) {
            foreach ($data as $key => $set) {
                if (is_array($set)) {
                    $pipe->hMSet($key, $set);
                    $pipe->expire($key, RedisStorageRepository::EXPIRATION_TIME);
                } else {
                    $pipe->Set($key, $set);
                }
            }
        });

        foreach ($data as $key => $value) {
            $items = explode("/", $key, 2);
            if (count($items) !== 2) {
                $this->logger->error("Can not get Node and Data from key: ${key}");
                continue;
            }

            $listPath = Redis::hGet($key, RedisStorageRepository::KEY_STREAM);
            if (!empty($listPath)) {
                Redis::lPush($items[0] . $listPath, json_encode(['/' . $items[1] => ['value' => $value, 'time' => time()]]));;
            }
        }

        return [];
    }

    public function readDev(ReadDeviceRequest $data): ReadDeviceDto
    {
        $var = [];

        foreach ($data->var as $key) {
            $var[$key] = array_intersect_key(Redis::hGetAll($data->Src->install . $key), array_flip($data->Dst->members));
        }

        return new ReadDeviceDto(['var' => $var,
            'Src' => ['Time' => time(), 'install' => $data->Src->install],
            'Dst' => ['Node' => $data->Dst->Node, 'install' => $data->Dst->install]
        ]);

    }

    public function writeDev(WriteDeviceRequest $data): WriteDeviceDto
    {
        Redis::pipeline(function ($pipe) use (&$data) {

            $keyDevice = $data->Src->install . RedisStorageRepository::KEY_DEVICE . $data->Src->Node;
            $pipe->hMSet($keyDevice, [RedisStorageRepository::KEY_TIMESTAMP => time()]);
            $pipe->expire($keyDevice, RedisStorageRepository::EXPIRATION_TIME);

            foreach ($data->var as $key => $set) {
                $key = $data->Src->install . $data->Dst->mount . $key;

                if (is_array($set)) {
                    $set[RedisStorageRepository::KEY_TIMESTAMP] = time();
                    $pipe->hMSet($key, $set);
                    $pipe->expire($key, RedisStorageRepository::EXPIRATION_TIME);
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

    public function getCommandsForInstallation(string $install, array $keys = []): array
    {
        $keys_read = [];
        if (empty($keys)) {
            foreach ($this->scanAllForMatch("{$install}*") as $value) {
                $keys_read[] = $value;
            }

        } else {
            foreach ($keys as $value) {
                $keys_read[] = $install . $value;
            }
        }

        $values_read = Redis::pipeline(function ($pipe) use (&$keys_read) {
            foreach ($keys_read as $key) {
                $pipe->hGet($key, RedisStorageRepository::KEY_VALUE);
            }
        });

        return array_filter(array_combine($keys_read, $values_read), fn($n) => $n !== false);
    }

    private function scanAllForMatch(string $pattern): Generator
    {
        $it = null;

        do {
            $result = Redis::scan($it, ['match' => $pattern, 'count' => 10000]);
            if ($result === false) {
                return;
            } else {
                list($it, $keys) = $result;
                foreach ($keys as $key) {
                    yield $key;
                }
            }

        } while ($it > 0);
    }
}
