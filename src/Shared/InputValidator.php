<?php
namespace Shared;

class InputValidator
{
    private array $errors = [];

    public function required(string $field, mixed $value, string $label = ''): self
    {
        if (trim((string)$value) === '') {
            $this->errors[$field] = ($label ?: $field) . ' est obligatoire.';
        }
        return $this;
    }

    public function email(string $field, mixed $value): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Adresse email invalide.';
        }
        return $this;
    }

    public function minLength(string $field, mixed $value, int $min): self
    {
        if (mb_strlen((string)$value) < $min) {
            $this->errors[$field] = "Minimum $min caractères requis.";
        }
        return $this;
    }

    public function positiveNumber(string $field, mixed $value): self
    {
        if (!is_numeric($value) || (float)$value <= 0) {
            $this->errors[$field] = 'Veuillez entrer un nombre positif.';
        }
        return $this;
    }

    public function isValid(): bool   { return empty($this->errors); }
    public function getErrors(): array { return $this->errors; }
    public function firstError(): string { return reset($this->errors) ?: ''; }
}
