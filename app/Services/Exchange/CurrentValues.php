<?php

namespace App\Services\Exchange;

use App\Loggers\DefaultLogger;
use App\Services\Exchange\Utils\CommandsHelper;
use App\Utils\CommonConst;
use App\Utils\PathHelper;
use Exception;

class CurrentValues
{
    private DefaultLogger $logger;


    /**
     * The constructor.
     *
     * @param DefaultLogger $logger
     */
    public function __construct(
        DefaultLogger $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * Store current value in var file
     *
     * @param $ctrlPath string path to controllers
     * @param $node string node of driver
     * @param $linkName string link name to path
     * @param $aplGroup string application group
     * @param $sch string schema identifier
     * @param $fileName string file name for current value
     * @param $data array commands to store in file
     * @return void
     * @throws Exception
     */
    public function storeCurrentValues(string $ctrlPath, string $node, string $linkName, string $aplGroup, string $sch, string $fileName, array $data): void
    {
        $pathOut = PathHelper::combine($ctrlPath, $linkName, $aplGroup, "INST_{$node}", $sch, "{$fileName}.out");
        PathHelper::createFileIfNotExist($pathOut);

        $values = '';
        foreach ($data as $key => $value) {
            $values .= $key . CommonConst::SEP_COMMAND . $value . CommonConst::EOL_COMMAND;
        }
        file_put_contents($pathOut, $values, LOCK_EX);
    }

    /**
     * Store current value in var file
     *
     * @param $ctrlPath string path to controllers
     * @param $node string node of driver
     * @param $linkName string link name to path
     * @param $aplGroup string application group
     * @param $sch string schema identifier
     * @param $fileName string file name for current value
     * @return array
     * @throws Exception
     */
    public function retrieveCurrentValues(string $ctrlPath, string $node, string $linkName, string $aplGroup, string $sch, string $fileName): array
    {
        $pathOut = PathHelper::combine($ctrlPath, $linkName, $aplGroup, "INST_{$node}", $sch, "{$fileName}.in");
        $content = @file_get_contents($pathOut);
        @unlink($pathOut);

        if ($content === false) {
            return [];
        }
        return CommandsHelper::dataToArray($content);
    }
}
