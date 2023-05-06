<?php

declare(strict_types=1);

namespace Core\Interfaces;

/**
 * Interface for validator engine. You can use different engines,
 * that implements ValidatorEngineInterface
 * So, for use validator, you must create ValidatorInterface and inject
 * into it ValidatorFactoryInterface (as in ValidatorFactory).
 */
interface ValidatorEngineInterface
{

    /**
     * Validate for required field
     * Usage: required
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function validate_required(string $field, mixed $value): bool;
    
    /**
     * Validate if not empty
     * Usage: notempty
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function validate_notempty(string $field, mixed $value): bool;
    
    /**
     * Validate min numeric
     * Usage: min:3|max:10
     * @param string $field
     * @param string|int|float $value
     * @param string|int|float $needle
     * @return bool
     */
    public function validate_min(string $field, string|int|float $value, string|int|float $needle): bool;
    
    /**
     * Validate if value is numeric
     * Usage: numeric
     * @param string $field
     * @param string|int|float $value
     * @return bool
     */
    public function validate_numeric(string $field, string|int|float $value): bool;
    
    /**
     * Validate for max value
     * Usage: min:10|max:20
     * @param string $field
     * @param string|int|float $value
     * @param string|int|float $needle
     * @return bool
     */
    public function validate_max(string $field, string|int|float $value, string|int|float $needle): bool;
    
    /**
     * Validate for minimum length in strings
     * Usage: maxlength:100
     * @param string $field
     * @param string|int|float $value
     * @param string|int|float $needle
     * @return bool
     */
    public function validate_minlength(string $field, string|int|float $value, string|int|float $needle): bool;
    
    /**
     * Validate for maximum length in strings
     * Usage: minlength:10
     * @param string $field
     * @param string|int|float $value
     * @param string|int|float $needle
     * @return bool
     */
    public function validate_maxlength(string $field, string|int|float $value, string|int|float $needle): bool;
    
    /**
     * Validate for email
     * Usage: email or email|required
     * @param string $field
     * @param string|int|float $value
     * @return bool
     */
    public function validate_email(string $field, string|int|float $value): bool;
    
    /**
     * Deep validation of email
     * Usage: validate_email_dns
     * @param string $field
     * @param string|int|float $value
     * @return bool
     */
    public function validate_email_dns(string $field, string|int|float $value): bool;
    
    /**
     * Valudate url
     * Usage: url
     * @param string $field
     * @param string|int|float $value
     * @return bool
     */
    public function validate_url(string $field, string|int|float $value): bool;
    
    /**
     * Validate for active url
     * Usage: url_active
     * @param string $field
     * @param string|int|float $value
     * @return bool
     */
    public function validate_url_active(string $field, string|int|float $value): bool;
    
    /**
     * Validate for date
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function validate_date(string $field, mixed $value): bool;
    
    /**
     * Validate for date in some format
     * @param string $field
     * @param mixed $value
     * @param string $needle
     * @return bool
     */
    public function validate_date_format(string $field, mixed $value, string $needle): bool;
    
    /**
     * Validate if date before date
     * @param string $field
     * @param mixed $value
     * @param string $needle
     * @return bool
     */
    public function validate_date_before(string $field, mixed $value, string $needle): bool;
    
    /**
     * Validate for date after date
     * @param string $field
     * @param mixed $value
     * @param string $needle
     * @return bool
     */
    public function validate_date_after(string $field, mixed $value, string $needle): bool;
    
    /**
     * Validate for boolean
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function validate_boolean(string $field, mixed $value): bool;
    
    /**
     * Set new validator error
     * @param string $field
     * @param string $message
     * @param mixed $needle
     * @return void
     */
    public function setError(string $field, string $message, mixed $needle = ''): void;
    
    /**
     * Set field name
     * @param string $field Field key
     * @param string $name Label for field
     * @return self
     */
    public function setName(string $field, string $name): self;

    /**
     * Set field names
     * @param array $fieldNames Array of field names
     * @return self
     */
    public function setNames(array $fieldNames): self;

    /**
     * Reset names
     * @return void
     */
    public function clearNames(): void;
    
    /**
     * Reset messages
     * @return void
     */
    public function clearMessages() : void;
    
    /**
     * Get validation messages
     * @return array
     */
    public function getMessages() : array;
}
