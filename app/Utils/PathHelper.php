<?php
declare(strict_types=1);

namespace App\Utils;

use Exception;

class PathHelper
{
    public static function combine(...$args): string
    {
        return implode(DIRECTORY_SEPARATOR, $args);
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

    public static function fixPathTraversal(string $path): string
    {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $stack = array();
        foreach ($path as $seg) {
            if ($seg == '..') {
                // Ignore this segment, remove last segment from stack
                array_pop($stack);
                continue;
            }

            if ($seg == '.') {
                // Ignore this segment
                continue;
            }

            $stack[] = $seg;
        }

        return implode(DIRECTORY_SEPARATOR, $stack);
    }

}
