<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NoForbiddenWords extends Constraint
{
    public string $message = 'Le mot "{{ word }}" n\'est pas autorisé.';
    public array $words = [];

    public function __construct(array $words = [], ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct(null, $groups, $payload);
        $this->words = $words;
        $this->message = $message ?? $this->message;
    }
}