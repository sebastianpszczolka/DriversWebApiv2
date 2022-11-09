<?php

declare(strict_types=1);

namespace App\Libraries;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class StringGenerator
{
    public function generatePassword(int $length = 10): string
    {
        // Defined chars that can be used in password
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Developer is lazy so he did not count char but defined length by strlen :)
        $charactersLength = strlen($characters);

        // Set up result
        $randomString = '';

        // Length cannot be under 3 chars, so if it is set up length to 10 chars
        if ($length < 3) {
            $length = 10;
        }

        // Get random char (from defined string) x times where x = length
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        // Return random string
        return $randomString;
    }

    public function getRandomString(int $length = 42): string
    {
        // Check that PHP parser has this method (using to render pseudo random bytes)
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            // If generator failed, throw runtime exception
            if ($bytes === false) {
                throw new RuntimeException('Unable to generate random string.');
            }

            // Generated string (with pointed length) without some chars
            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        // If method is not supported, then create string with all chars which are need to generator
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Return random string
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public function getUniqueFileName(string $path, string $ext): string
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $filename = sprintf('%s.%s', Str::random(16), $ext);

        // Generate file name until there will be unique
        while (File::exists(sprintf('%s%s%s', $path, DIRECTORY_SEPARATOR, $filename))) {
            $filename = sprintf('%s.%s', Str::random(16), $ext);
        }

        return $filename;
    }
}