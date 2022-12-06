<?php
declare(strict_types=1);

namespace App\Services\Exchange;

use App\Libraries\Paths;
use App\Loggers\DefaultLogger;
use App\Services\Exchange\Utils\Commands;
use App\Services\Exchange\Utils\CommandsHelper;
use App\Services\Exchange\Utils\PathHelper;
use Illuminate\Support\Facades\Config;
use Exception;

class Exchange
{
    private DefaultLogger $logger;
    private LogCreator $logCreator;
    private CurrentValues $currentValues;
    private Paths $paths;

    private const DATA_FIELD = 'data';


    /**
     * The constructor.
     *
     * @param DefaultLogger $logger
     * @param Paths $paths
     * @param LogCreator $logCreator Creating logs
     * @param CurrentValues $currentValues Current values stores and read
     */
    public function __construct(DefaultLogger $logger,
                                Paths         $paths,
                                LogCreator    $logCreator,
                                CurrentValues $currentValues)
    {

        $this->logger = $logger;
        $this->paths = $paths;
        $this->logCreator = $logCreator;
        $this->currentValues = $currentValues;
    }

    /**
     * Create a new log.
     *
     * @param array $data The form data
     *
     * @return array
     * @throws Exception
     */
    public function exchangeParameters(array $data): array
    {
        $ctrlPath = $this->paths->getControllersPath();

        if (empty($ctrlPath)) {
            throw new Exception('Missing configuration for ctrl_path');
        }

        $node = CommandsHelper::trimCorrectCmd($data[Commands::NODE]);
        $linkName = CommandsHelper::trimCorrectCmd($data[Commands::LINK_NAME]);
        $aplGroup = CommandsHelper::trimCorrectCmd($data[Commands::APLGROUP]);
        $sch = CommandsHelper::trimCorrectCmd($data[Commands::SCH]);
        $fileName = CommandsHelper::trimCorrectCmd($data[Commands::FILE_NAME]);

        $this->createIndexContent($ctrlPath, $node, $linkName, $aplGroup, $sch);
        $this->currentValues->storeCurrentValues($ctrlPath, $node, $linkName, $aplGroup, $sch, $fileName, $data[Exchange::DATA_FIELD]);
        $retValue = $this->currentValues->retrieveCurrentValues($ctrlPath, $node, $linkName, $aplGroup, $sch, $fileName);

        $this->logCreator->manageLog($ctrlPath, $node, $linkName, $aplGroup, $sch, $data[Exchange::DATA_FIELD]);

        return [Commands::NODE => $node, Commands::LINK_NAME => $linkName, Commands::APLGROUP => $aplGroup,
            Commands::SCH => $sch, Commands::FILE_NAME => $fileName, Exchange::DATA_FIELD => $retValue
        ];
    }

    /**
     * Create INDEX file
     *
     * @param $ctrlPath string path to controllers
     * @param $node string node of driver
     * @param $linkName string link name to path
     * @param $aplGroup string application group
     * @param $sch string schema identifier
     * @return void
     * @throws Exception
     */
    private function createIndexContent(string $ctrlPath, string $node, string $linkName, string $aplGroup, string $sch): void
    {
        $pathIndex = PathHelper::combine($ctrlPath, 'INDEX', $node);
        $pathOutIndexContent = PathHelper::combine($ctrlPath, $linkName, $aplGroup, "INST_{$node}", $sch);

        PathHelper::createFileIfNotExist($pathIndex);
        PathHelper::createDirectoryIfNotExist($pathOutIndexContent);

        file_put_contents($pathIndex, $pathOutIndexContent, LOCK_EX);
    }
}
