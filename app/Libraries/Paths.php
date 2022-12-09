<?php
declare(strict_types=1);

namespace App\Libraries;

use Illuminate\Support\Facades\Config;

class Paths
{

    /**
     * Return base path for installation obtained from INDEX file
     * @param string $instBarcode
     * @return string|null
     */
    public function getInstBasePath(string $instBarcode): ?string
    {
        $trimmedBarcode = trim($instBarcode);
        $instFilePath = sprintf('%s%s', $this->getIndexPath(), $trimmedBarcode);

        if (file_exists($instFilePath)) {
            $path = file_get_contents($instFilePath);
            return $this->adjustPath($path);
        }

        return null;
    }

    /**
     * Reformat path based on environment
     *
     * @param string $path
     *
     * @return string
     */
    private function adjustPath(string $path): string
    {
        if (app()->isLocal()) {

            $localControllersPathPart = Config::get('app.paths.local_part_path');
            $serverControllersPathPart = Config::get('app.paths.server_part_path');

            return str_replace($serverControllersPathPart, $localControllersPathPart, $path);
        }

        return $path;
    }

    /**
     * @param ...$args
     * @return string
     */
    public function joinPath(...$args): string
    {
        $args[0] = rtrim($args[0], '/');

        return implode(DIRECTORY_SEPARATOR, $args);
    }

    /**
     * Return index path from app config
     *
     * @return string
     */
    public function getIndexPath(): string
    {
        return Config::get('app.paths.index_path');
    }

    /**
     * Return Controllers path from app config (basing on environment)
     *
     * @return string
     */
    public function getControllersPath(): string
    {
        return Config::get('app.paths.controllers_path');
    }

    /**
     * Return schema path from app config (basing on environment)
     *
     * @return string
     */
    public function getSchemaPath(): string
    {
        return Config::get('app.paths.sch_path');
    }

    function remove_dot_segments(string $path): string
    {
        $path = explode('/', $path);
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

        return implode('/', $stack);
    }
}
