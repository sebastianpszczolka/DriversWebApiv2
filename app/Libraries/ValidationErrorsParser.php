<?php
declare(strict_types=1);

namespace App\Libraries;

use Illuminate\Support\MessageBag;

class ValidationErrorsParser
{
    /**
     * Parse validator error messages
     *
     * @param MessageBag $validationErrors
     *
     * @return array
     */
    public function parse(MessageBag $validationErrors): array
    {
        // Formatting messages to acceptable format for response
        $errors = [];

        foreach ($validationErrors->getMessages() as $key => $message) {
            $errors[] = $this->createMessage($message[0] ?? 'Unknown error', 'error', $key);
        }

        return $errors;
    }

    /**
     * Return parsed message
     *
     * @param string $message
     * @param string $type
     * @param string|null $target
     *
     * @return array
     */
    private function createMessage(string $message, string $type, ?string $target = null): array
    {
        return [
            'message' => $message,
            'type' => $type,
            'target' => $target
        ];
    }

}
