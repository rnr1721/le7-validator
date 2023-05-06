<?php

namespace Core\Utils;

use Core\Interfaces\ValidatorEngineInterface;
use \DateTime;
use const FILTER_VALIDATE_EMAIL;
use const FILTER_VALIDATE_URL;
use function floatval,
             is_numeric,
             is_bool,
             in_array,
             array_key_exists,
             strval,
             intval,
             strlen,
             filter_var,
             ltrim,
             stristr,
             function_exists,
             defined,
             idn_to_ascii,
             checkdnsrr,
             str_starts_with,
             parse_url,
             date_parse_from_format,
             strtotime;

/**
 * Default validator engine
 */
class ValidatorEngine implements ValidatorEngineInterface
{

    protected array $names = array();
    protected array $messages = array();
    protected array $allowedUrl = array('http://', 'https://');

    /**
     * @inheritdoc
     */
    public function validate_required(string $field, mixed $value): bool
    {
        if ($value === '' || $value === []) {
            $this->setError($field, _('is required field') . ' ');
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_notempty(string $field, mixed $value): bool
    {
        if (empty($value)) {
            $this->setError($field, _('is required field') . ' ');
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_min(string $field, string|int|float $value, string|int|float $needle): bool
    {
        if (!$this->validate_numeric($field, $value)) {
            return false;
        }
        $valueFloat = floatval($value);
        $needleFloat = floatval($needle);
        if ($valueFloat < $needleFloat) {
            $this->setError($field, _('minimal value is') . ' ', $needleFloat);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_numeric(string $field, string|int|float $value): bool
    {
        if (!is_numeric($value)) {
            $this->setError($field, _('must be numeric'));
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_max(string $field, string|int|float $value, string|int|float $needle): bool
    {
        if (!$this->validate_numeric($field, $value)) {
            return false;
        }
        $valueFloat = floatval($value);
        $needleFloat = floatval($needle);
        if ($valueFloat > $needleFloat) {
            $this->setError($field, _('maximal value is') . ' ', $needleFloat);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_minlength(string $field, string|int|float $value, string|int|float $needle): bool
    {
        if (empty($value)) {
            return true;
        }
        $valueString = strval($value);
        $needleInt = intval($needle);
        if (strlen($valueString) < $needleInt) {
            $this->setError($field, _('minimal length is') . ' ', $needleInt);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_maxlength(string $field, string|int|float $value, string|int|float $needle): bool
    {
        if (empty($value)) {
            return true;
        }
        $valueString = strval($value);
        $needleInt = intval($needle);
        if (strlen($valueString) > $needleInt) {
            $this->setError($field, _('maximal length is') . ' ', $needleInt);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_email(string $field, string|int|float $value): bool
    {
        if (empty($value)) {
            return true;
        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->setError($field, _('not correct email:'), $value);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_email_dns(string $field, string|int|float $value): bool
    {
        if (!$this->validate_email($field, $value)) {
            return false;
        }
        $domain = ltrim(stristr((string) $value, '@'), '@') . '.';
        if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46')) {
            $domain = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);
        }
        if (checkdnsrr($domain)) {
            return true;
        }
        $this->setError($field, _('domain not correct'), $value);
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validate_url(string $field, string|int|float $value): bool
    {
        if (empty($value)) {
            return true;
        }
        foreach ($this->allowedUrl as $prefix) {
            if (str_starts_with((string) $value, $prefix)) {
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            }
        }
        $this->setError($field, _('not correct') . ': ' . $value);
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validate_url_active(string $field, string|int|float $value): bool
    {
        if (empty($value)) {
            return true;
        }
        foreach ($this->allowedUrl as $prefix) {
            if (str_starts_with((string) $value, $prefix)) {
                $host = parse_url(strtolower((string) $value), PHP_URL_HOST);
                if (checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA') || checkdnsrr($host, 'CNAME')) {
                    return true;
                }
            }
        }
        $this->setError($field, _('not active') . ': ', $value);
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validate_date(string $field, mixed $value): bool
    {
        if ($value instanceof DateTime) {
            $isDate = true;
        } else {
            $isDate = strtotime($value) !== false;
        }
        if (!$isDate) {
            $this->setError($field, _('incorrect date') . ': ', $value);
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_date_format(string $field, mixed $value, string $needle): bool
    {
        $parsed = date_parse_from_format($needle, $value);
        $result = $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
        if (!$result) {
            $this->setError($field, _('correct date format') . ': ', $needle);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validate_date_before(string $field, mixed $value, string $needle): bool
    {
        $vtime = ($value instanceof DateTime) ? $value->getTimestamp() : strtotime($value);
        echo $value;
        $ptime = (strtotime($needle));
        if ($vtime < $ptime) {
            return true;
        }
        $this->setError($field, _('The date must not exceed') . ' ', $needle);
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validate_date_after(string $field, mixed $value, string $needle): bool
    {
        $vtime = ($value instanceof DateTime) ? $value->getTimestamp() : strtotime($value);
        $ptime = (strtotime($needle));
        if ($vtime > $ptime) {
            return true;
        }
        $this->setError($field, _('The date must not be below') . ' ', $needle);
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validate_boolean(string $field, mixed $value): bool
    {
        if (is_bool($value)) {
            return true;
        }
        $this->setError($field, _('must be boolean'));
        return false;
    }

    /**
     * @inheritdoc
     */
    public function setError(string $field, string $message, mixed $needle = ''): void
    {
        if (array_key_exists($field, $this->names)) {
            $field = $this->names[$field];
        }
        $message = $field . ': ' . $message . $needle;
        if (!in_array($message, $this->messages)) {
            $this->messages[] = $message;
        }
    }

    /**
     * @inheritdoc
     */
    public function setName(string $field, string $name): self
    {
        $this->names[$field] = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setNames(array $fieldNames): self
    {
        foreach ($fieldNames as $field => $name) {
            $this->setName($field, $name);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearMessages(): void
    {
        $this->messages = [];
    }

    /**
     * @inheritdoc
     */
    public function clearNames(): void
    {
        $this->names = [];
    }

    /**
     * @inheritdoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

}
