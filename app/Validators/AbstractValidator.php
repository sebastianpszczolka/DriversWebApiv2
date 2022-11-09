<?php
declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\ValidationException;
use Illuminate\Validation\Validator;

class AbstractValidator
{
    protected array $rules;
    protected array $data;
    protected array $messages;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->rules = [];
        $this->messages = [];
    }

    public function validate(): void
    {
        /** @var Validator $validator */
        $validator = validator($this->data, $this->rules, $this->messages);

        if ($validator->fails()) {
            throw new ValidationException($validator->messages());
        }
    }

    public function isValid(): bool
    {
        /** @var Validator $validator */
        $validator = validator($this->data, $this->rules, $this->messages);

        return !$validator->fails();
    }

}
