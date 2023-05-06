<?php

declare(strict_types=1);

namespace Core\Interfaces;

/**
 * Main interface for validator.
 * It contain all methods that need for validation.
 * This interface contains common methods, and the concrete implementation
 * is in ValidatorInterface
 */
interface ValidatorInterface
{

    /**
     * Validate loaded fields
     * @return bool
     */
    public function validate(): bool;

    /**
     * Set field keys for ignore
     * @param array|string $fields Field arrays
     * @return self
     */
    public function setIgnored(array|string $fields): self;

    /**
     * Set field values
     * @param array $fields Fields array
     * @return self
     */
    public function setValues(array $fields): self;

    /**
     * Set value for field
     * @param string $field Field key
     * @param mixed $value Field value
     * @return self
     */
    public function setValue(string $field, mixed $value): self;

    /**
     * Set rules
     * @param array $rules Rules array
     * @return self
     */
    public function setRules(array $rules): self;

    /**
     * Set short rule
     * @param string $field Field key
     * @param string $rule Right syntaxis rule
     * @return self
     */
    public function setRule(string $field, string $rule): self;

    /**
     * Set full rule for field
     * @param string $field Field key
     * @param mixed $value Value that check
     * @param string $rule Rule with right syntaxis
     * @param string $name Label of field
     * @return self
     */
    public function setFullRule(string $field, mixed $value, string $rule, string $name = ''): self;

    /**
     * Reset validator rules for next check
     * @return self
     */
    public function reset(): self;

    /**
     * Get validator messages
     * @return array
     */
    public function getMessages(): array;
}
