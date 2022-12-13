<?php
declare(strict_types=1);

namespace App\Services\Exchange\Utils;

use App\Utils\CommonConst;

class CommandsHelper
{
    public static function dataToArray(string $data): array
    {
        $ret = [];
        $lines = array_filter(explode(CommonConst::EOL_COMMAND, $data));

        foreach ($lines as $line) {
            $kv = explode(CommonConst::SEP_COMMAND, $line);
            $len = count($kv);
            if ($len == 2) {
                $ret[$kv[0]] = $kv[1];
            } else {
                $ret[$kv[0]] = '';
            }
        }

        return $ret;
    }

    /**
     * trim unneeded character in command etc
     * @param $command string
     * @return string
     */
    public static function trimCorrectCmd(string $command): string
    {
        return rtrim($command, " \t\n\r\0\x0B;");
    }

}
