<?php
declare(strict_types=1);

namespace App\Services\Exchange\Utils;

use Exception;

class PathHelper
{
    public static function combine(): string
    {
        $paths = func_get_args();

        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * Create file (if not exist) from path and create parent directory if needed.
     *
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    public static function createFileIfNotExist($filePath)
    {
        if (file_exists($filePath)) {
            return;
        }

        $dirName = dirname($filePath);
        self::createDirectoryIfNotExist($dirName);

        touch($filePath);

        if (!file_exists($filePath)) {
            throw new Exception("Can not create file: {$filePath}");
        }
    }

    /**
     * Create directory from path.
     *
     * @param string $dirPath
     * @return void
     * @throws Exception
     */
    public static function createDirectoryIfNotExist(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        if (!is_dir($dirPath)) {
            throw new Exception("Can not create directory: {$dirPath}");
        }
    }

}
