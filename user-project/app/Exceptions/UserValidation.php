<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class UserValidation extends Exception {

    protected MessageBag $errors;

    public function __construct($errors) {
        if ($errors instanceof MessageBag) {
            $this->errors = $errors;
        } else if (is_array($errors)) {
            $this->errors = new MessageBag($errors);
        } else {
            $this->errors = new MessageBag(['error' => [(string) $errors]]);
        }

        parent::__construct('Erro de validação');
    }

    public function getErrors(): MessageBag {

        return $this->errors;
    }
}
