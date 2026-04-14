<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueAtributoRule implements Rule
{
    protected $message = 'No puedes seleccionar el mismo atributo dos veces.';

    public function passes($attribute, $value): bool
    {
        if (!is_array($value)) {
            return true;
        }

        $atributoIds = array_filter(array_map(function ($item) {
            return $item['id_atributo'] ?? null;
        }, $value));

        return count($atributoIds) === count(array_unique($atributoIds));
    }

    public function message(): string
    {
        return $this->message;
    }
}