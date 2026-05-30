<?php

namespace Shared;

/**
 * Fluent validator for user input fields.
 * Rules are chained and errors are collected per field name.
 *
 * @package Shared
 */
class InputValidator
{
    /** @var array<string, string> Validation errors indexed by field name */
    private array $errors = [];

    /**
     * Fails if the value is empty or whitespace-only.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $label Human-readable field name for the error message
     * @return self
     */
    public function required(string $field, mixed $value, string $label = ''): self
    {
        if (trim((string)$value) === '') {
            $this->errors[$field] = ($label ?: $field) . ' est obligatoire.';
        }
        return $this;
    }

    /**
     * Fails if the value is not a valid email address.
     *
     * @param string $field
     * @param mixed  $value
     * @return self
     */
    public function email(string $field, mixed $value): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Adresse email invalide.';
        }
        return $this;
    }

    /**
     * Fails if the string length (in characters) is below the minimum.
     *
     * @param string $field
     * @param mixed  $value
     * @param int    $min
     * @return self
     */
    public function minLength(string $field, mixed $value, int $min): self
    {
        if (mb_strlen((string)$value) < $min) {
            $this->errors[$field] = "Minimum $min caractères requis.";
        }
        return $this;
    }

    /**
     * Fails if the value is not a positive number.
     *
     * @param string $field
     * @param mixed  $value
     * @return self
     */
    public function positiveNumber(string $field, mixed $value): self
    {
        if (!is_numeric($value) || (float)$value <= 0) {
            $this->errors[$field] = 'Veuillez entrer un nombre positif.';
        }
        return $this;
    }

    /**
     * @return bool True if no validation errors were recorded
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string The first error message, or an empty string if none
     */
    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }
}