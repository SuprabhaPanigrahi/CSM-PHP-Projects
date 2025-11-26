<?php

class Validator
{
    private array $errors = [];

    public function required(string $field, $value, string $label = null): void
    {
        $label = $label ?? ucfirst($field);
        if (trim((string)$value) === '') {
            $this->errors[$field][] = "$label is required.";
        }
    }

    public function email(string $field, $value, string $label = null): void
    {
        $label = $label ?? ucfirst($field);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "$label must be a valid email address.";
        }
    }

    public function integer(string $field, $value, string $label = null): void
    {
        $label = $label ?? ucfirst($field);
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field][] = "$label must be an integer.";
        }
    }

    public function min(string $field, $value, int $min, string $label = null): void
    {
        $label = $label ?? ucfirst($field);
        if ((int)$value < $min) {
            $this->errors[$field][] = "$label must be at least $min.";
        }
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
