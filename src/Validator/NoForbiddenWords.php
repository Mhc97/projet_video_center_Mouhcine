<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NoForbiddenWords extends Constraint
{
    public string $message = 'Le mot "{{ word }}" n\'est pas autorisé.';
    public array $words = [];
}